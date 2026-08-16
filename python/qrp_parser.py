import re
import sys
import json
from decimal import Decimal


# ============================================================
# CONFIGURACIÓN
# ============================================================

PALABRAS_COLEGIATURA = [
    "COLEGIATURA",
    "PAGO DE COLEGIATURA",
    "PAGO COLEGIATURA",
    "MENSUALIDAD",
]

PALABRAS_EXAMEN = [
    "EXAMEN",
    "EXTRAORDINARIO",
    "ORDINARIO",
]


# ============================================================
# VALIDACIONES
# ============================================================

def es_recibo(valor):
    """
    Un recibo normalmente tiene entre 4 y 6 dígitos.
    """

    valor = str(valor).strip()

    return bool(
        re.fullmatch(r"\d{4,6}", valor)
    )


def es_fecha(valor):

    valor = str(valor).strip()

    return bool(
        re.fullmatch(
            r"\d{2}/\d{2}/\d{4}",
            valor
        )
    )


def es_hora(valor):

    valor = str(valor).strip()

    return bool(
        re.fullmatch(
            r"\d{2}:\d{2}:\d{2}",
            valor
        )
    )


def es_importe(valor):
    """
    Reconoce:

        200.00
        800.00
        0.00
        1,200.00
        $200.00
    """

    valor = str(valor).strip()

    return bool(
        re.fullmatch(
            r"\$?[\d,]+\.\d{2}",
            valor
        )
    )


def convertir_decimal(valor):

    valor = str(valor)

    valor = valor.replace("$", "")
    valor = valor.replace(",", "")

    return Decimal(valor)


# ============================================================
# EXTRAER STRINGS DEL QRP
# ============================================================

def extraer_strings_qrp(data):
    """
    Extrae textos legibles del QRP.

    Conserva:

    - textos normales
    - importes
    - fechas
    - horas
    - recibos
    - números del 1 al 52

    El QRP contiene mucho texto interno de formato,
    por eso se eliminan cadenas de un solo carácter
    que no sean números del 1 al 52.
    """

    patron = re.compile(
        rb'(?:[\x20-\x7e]\x00){1,}'
    )

    resultado = []

    for m in patron.finditer(data):

        try:

            texto = (
                m.group()
                .decode("utf-16le")
                .strip()
            )

            if not texto:
                continue

            if len(texto) >= 2:

                resultado.append(texto)

                continue

            if texto.isdigit():

                numero = int(texto)

                if 1 <= numero <= 52:

                    resultado.append(texto)

        except UnicodeDecodeError:

            pass

    return resultado


# ============================================================
# CONTAR COLEGIATURAS
# ============================================================

def contar_colegiaturas(concepto):
    """
    Cuenta cuántas colegiaturas representa el concepto.

    Ejemplos:

        "1"              -> 1
        "23"             -> 1
        "52"             -> 1
        "23,24"          -> 2
        "23, 24"         -> 2
        "23,24,25"       -> 3
        "23, 24, 25, 26" -> 4
        "1,2,3"          -> 3
        "Colegiatura"    -> 1
        "Mensualidad"    -> 1
    """

    texto = str(concepto).strip().upper()

    if not texto:

        return 0

    for palabra in PALABRAS_COLEGIATURA:

        if palabra in texto:

            return 1

    texto = texto.replace(";", ",")
    texto = texto.replace("/", ",")
    texto = texto.replace(" ", "")

    if not re.fullmatch(
        r"\d+(,\d+)*",
        texto
    ):

        return 0

    partes = texto.split(",")

    cantidad = 0

    for parte in partes:

        try:

            numero = int(parte)

        except ValueError:

            continue

        if 1 <= numero <= 52:

            cantidad += 1

    return cantidad


# ============================================================
# CLASIFICAR CONCEPTO
# ============================================================

def clasificar(concepto):
    """
    Clasifica el concepto como:

        Colegiatura
        Examen
        Otros
        Sin concepto
    """

    texto = str(concepto).strip().upper()

    if not texto:

        return "Sin concepto"

    # --------------------------------------------------------
    # COLEGIATURA POR PALABRA
    # --------------------------------------------------------

    for palabra in PALABRAS_COLEGIATURA:

        if palabra in texto:

            return "Colegiatura"

    # --------------------------------------------------------
    # COLEGIATURA POR NÚMEROS
    # --------------------------------------------------------

    texto_normalizado = texto

    texto_normalizado = texto_normalizado.replace(
        ";",
        ","
    )

    texto_normalizado = texto_normalizado.replace(
        "/",
        ","
    )

    texto_normalizado = texto_normalizado.replace(
        " ",
        ""
    )

    if re.fullmatch(
        r"\d+(,\d+)*",
        texto_normalizado
    ):

        partes = texto_normalizado.split(",")

        numeros_validos = True

        for parte in partes:

            try:

                numero = int(parte)

            except ValueError:

                numeros_validos = False

                break

            if not 1 <= numero <= 52:

                numeros_validos = False

                break

        if numeros_validos:

            return "Colegiatura"

    # --------------------------------------------------------
    # EXAMEN
    # --------------------------------------------------------

    for palabra in PALABRAS_EXAMEN:

        if palabra in texto:

            return "Examen"

    # --------------------------------------------------------
    # OTROS
    # --------------------------------------------------------

    return "Otros"


# ============================================================
# EXTRAER MOVIMIENTOS
# ============================================================

def extraer_movimientos(strings):
    """
    Extrae los movimientos reales del QRP.

    Estructura observada:

        No. Rcbo.
        Fecha Pago
        Colegiatura
        Recargo
        usuario
        Semana / Mes / Concepto
        Total
        Hora Pago
    """

    movimientos = []

    i = 0

    while i < len(strings):

        # ----------------------------------------------------
        # RECIBO
        # ----------------------------------------------------

        if not es_recibo(strings[i]):

            i += 1

            continue

        recibo = strings[i]

        # ----------------------------------------------------
        # FECHA
        # ----------------------------------------------------

        if i + 1 >= len(strings):

            i += 1

            continue

        if not es_fecha(strings[i + 1]):

            i += 1

            continue

        fecha = strings[i + 1]

        # ----------------------------------------------------
        # IMPORTE
        # ----------------------------------------------------

        if i + 2 >= len(strings):

            i += 1

            continue

        importe = strings[i + 2]

        if not es_importe(importe):

            i += 1

            continue

        # ----------------------------------------------------
        # RECARGO
        # ----------------------------------------------------

        if i + 3 >= len(strings):

            i += 1

            continue

        recargo = strings[i + 3]

        if not es_importe(recargo):

            i += 1

            continue

        # ----------------------------------------------------
        # USUARIO
        # ----------------------------------------------------

        if i + 4 >= len(strings):

            i += 1

            continue

        usuario = strings[i + 4]

        # ----------------------------------------------------
        # BUSCAR HORA
        # ----------------------------------------------------

        j = i + 5

        hora = None

        posicion_hora = None

        limite = min(
            i + 15,
            len(strings)
        )

        while j < limite:

            if es_hora(strings[j]):

                hora = strings[j]

                posicion_hora = j

                break

            j += 1

        if posicion_hora is None:

            i += 1

            continue

        # ----------------------------------------------------
        # TOTAL
        # ----------------------------------------------------

        posicion_total = posicion_hora - 1

        if posicion_total < i + 5:

            i += 1

            continue

        total = strings[posicion_total]

        if not es_importe(total):

            i += 1

            continue

        # ----------------------------------------------------
        # CONCEPTO
        # ----------------------------------------------------

        concepto_partes = strings[
            i + 5:posicion_total
        ]

        concepto_validos = []

        for parte in concepto_partes:

            parte = str(parte).strip()

            if not parte:

                continue

            # ----------------------------------------------
            # NÚMERO INDIVIDUAL
            # ----------------------------------------------

            if parte.isdigit():

                numero_concepto = int(parte)

                if 1 <= numero_concepto <= 52:

                    concepto_validos.append(parte)

                    continue

            # ----------------------------------------------
            # VARIOS NÚMEROS SEPARADOS POR COMAS
            # ----------------------------------------------

            if re.fullmatch(
                r"\d+(,\d+)+",
                parte
            ):

                numeros_validos = []

                for numero_texto in parte.split(","):

                    try:

                        numero_concepto = int(
                            numero_texto
                        )

                    except ValueError:

                        continue

                    if 1 <= numero_concepto <= 52:

                        numeros_validos.append(
                            numero_texto
                        )

                if numeros_validos:

                    concepto_validos.append(
                        ",".join(numeros_validos)
                    )

                    continue

            # ----------------------------------------------
            # TEXTO NORMAL
            # ----------------------------------------------

            if len(parte) >= 2:

                concepto_validos.append(parte)

        concepto = " ".join(
            concepto_validos
        ).strip()

        # ----------------------------------------------------
        # GUARDAR MOVIMIENTO
        # ----------------------------------------------------

        movimientos.append({

            "recibo": recibo,

            "fecha": fecha,

            "importe": convertir_decimal(
                importe
            ),

            "recargo": convertir_decimal(
                recargo
            ),

            "usuario": usuario,

            "concepto": concepto,

            "total": convertir_decimal(
                total
            ),

            "hora": hora,

        })

        # ----------------------------------------------------
        # CONTINUAR DESPUÉS DE LA HORA
        # ----------------------------------------------------

        i = posicion_hora + 1

    return movimientos


# ============================================================
# ANALIZAR MOVIMIENTOS
# ============================================================

def analizar(movimientos):

    resumen = {

        "Colegiatura": {

            "cantidad": 0,

            "importe": Decimal("0.00")

        },

        "Examen": {

            "cantidad": 0,

            "importe": Decimal("0.00")

        },

        "Otros": {

            "cantidad": 0,

            "importe": Decimal("0.00")

        },

        "Sin concepto": {

            "cantidad": 0,

            "importe": Decimal("0.00")

        }

    }

    for movimiento in movimientos:

        concepto = movimiento["concepto"]

        categoria = clasificar(
            concepto
        )

        movimiento["categoria"] = categoria

        if categoria == "Colegiatura":

            cantidad = contar_colegiaturas(
                concepto
            )

            movimiento[
                "cantidad_colegiaturas"
            ] = cantidad

            resumen[
                "Colegiatura"
            ]["cantidad"] += cantidad

        else:

            movimiento[
                "cantidad_colegiaturas"
            ] = 0

            resumen[
                categoria
            ]["cantidad"] += 1

        resumen[
            categoria
        ]["importe"] += movimiento["total"]

    return resumen


# ============================================================
# CONVERTIR DECIMAL A JSON
# ============================================================

def decimal_a_json(obj):
    """
    Convierte Decimal y estructuras anidadas
    a valores compatibles con JSON.
    """

    if isinstance(obj, Decimal):

        return float(obj)

    if isinstance(obj, dict):

        return {
            clave: decimal_a_json(valor)
            for clave, valor in obj.items()
        }

    if isinstance(obj, list):

        return [
            decimal_a_json(valor)
            for valor in obj
        ]

    return obj


# ============================================================
# PROCESAR ARCHIVO QRP
# ============================================================

def procesar_archivo(ruta):

    try:

        # ----------------------------------------------------
        # LEER ARCHIVO
        # ----------------------------------------------------

        with open(
            ruta,
            "rb"
        ) as f:

            data = f.read()

        # ----------------------------------------------------
        # EXTRAER STRINGS
        # ----------------------------------------------------

        strings = extraer_strings_qrp(
            data
        )

        # ----------------------------------------------------
        # EXTRAER MOVIMIENTOS
        # ----------------------------------------------------

        movimientos = extraer_movimientos(
            strings
        )

        if not movimientos:

            return {

                "success": False,

                "message":
                    "No se pudieron detectar movimientos "
                    "en el archivo QRP.",

                "movimientos": [],

                "resumen": {}

            }

        # ----------------------------------------------------
        # ANALIZAR
        # ----------------------------------------------------

        resumen = analizar(
            movimientos
        )

        # ----------------------------------------------------
        # RESULTADO
        # ----------------------------------------------------

        resultado = {

            "success": True,

            "message":
                "Archivo analizado correctamente.",

            "movimientos":
                movimientos,

            "resumen":
                resumen,

            "total_movimientos":
                len(movimientos)

        }

        return decimal_a_json(
            resultado
        )

    except Exception as error:

        return {

            "success": False,

            "message":
                f"Error al procesar el QRP: {error}",

            "movimientos": [],

            "resumen": {}

        }


# ============================================================
# PUNTO DE ENTRADA
# ============================================================

if __name__ == "__main__":

    # --------------------------------------------------------
    # VALIDAR ARGUMENTOS
    # --------------------------------------------------------

    if len(sys.argv) < 2:

        resultado = {

            "success": False,

            "message":
                "No se recibió la ruta del archivo QRP.",

            "movimientos": [],

            "resumen": {}

        }

        print(
            json.dumps(
                resultado,
                ensure_ascii=False
            )
        )

        sys.exit(1)

    # --------------------------------------------------------
    # RUTA DEL ARCHIVO
    # --------------------------------------------------------

    ruta = sys.argv[1]

    # --------------------------------------------------------
    # PROCESAR
    # --------------------------------------------------------

    resultado = procesar_archivo(
        ruta
    )

    # --------------------------------------------------------
    # DEVOLVER JSON A LARAVEL
    # --------------------------------------------------------

    print(
        json.dumps(
            resultado,
            ensure_ascii=False
        )
    )

    # --------------------------------------------------------
    # CÓDIGO DE SALIDA
    # --------------------------------------------------------

    if not resultado.get("success", False):

        sys.exit(1)

    sys.exit(0)