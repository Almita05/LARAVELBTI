import re
import sys
import json
from decimal import Decimal


PALABRAS_COLEGIATURA = [
    "COLEGIATURA",
    "PAGO DE COLEGIATURA",
    "PAGO COLEGIATURA",
    "MENSUALIDAD",
]

PALABRAS_EXAMEN = [
    "EXAMEN",
    "ORDINARIO",
]


# ============================================================
# LIMPIEZA DE TEXTOS QRP
# ============================================================

def limpiar_texto_qrp(texto):
    """
    Limpia caracteres extraños que pueden aparecer
    al extraer texto UTF-16LE de archivos QRP.

    Ejemplos:

        angy !ð !ð
            -> angy

        Alma !� !�
            -> Alma

        toño !� colegiatura !�
            -> toño colegiatura

        ANGY !ð !ð 19
            -> ANGY 19
    """

    if texto is None:
        return ""

    texto = str(texto)

    # --------------------------------------------------------
    # Reemplazar caracteres de reemplazo Unicode
    # --------------------------------------------------------

    texto = texto.replace("\ufffd", " ")

    # --------------------------------------------------------
    # Eliminar patrones conocidos de basura
    #
    # !ð
    # !� 
    # etc.
    # --------------------------------------------------------

    texto = re.sub(
        r"!\s*[\ufffdðÐ�]+",
        " ",
        texto,
        flags=re.IGNORECASE
    )

    # --------------------------------------------------------
    # Eliminar caracteres de control
    # --------------------------------------------------------

    texto = "".join(
        caracter
        for caracter in texto
        if caracter.isprintable()
        or caracter in "\n\t"
    )

    # --------------------------------------------------------
    # Eliminar caracteres extraños que suelen venir
    # de la codificación del QRP.
    #
    # Conservamos letras, números, espacios y puntuación
    # normal.
    # --------------------------------------------------------

    texto = re.sub(
        r"[^\w\sÁÉÍÓÚÜÑáéíóúüñ.,;:/()\-#$%]",
        " ",
        texto,
        flags=re.UNICODE
    )

    # --------------------------------------------------------
    # Quitar espacios repetidos
    # --------------------------------------------------------

    texto = re.sub(
        r"\s+",
        " ",
        texto
    ).strip()

    return texto


# ============================================================
# VALIDACIONES
# ============================================================

def es_recibo(valor):

    valor = str(valor).strip()

    return bool(
        re.fullmatch(
            r"\d{4,6}",
            valor
        )
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


def es_semana(valor):

    texto = str(valor).strip().upper()

    texto = texto.replace(
        "SEMANA",
        ""
    )

    texto = texto.replace(
        ":",
        ""
    )

    texto = texto.replace(
        " ",
        ""
    )

    if not texto.isdigit():
        return False

    numero = int(texto)

    return 1 <= numero <= 52


# ============================================================
# EXTRAER STRINGS
# ============================================================

def extraer_strings_qrp(data):
    """
    Extrae cadenas legibles del archivo QRP.

    Intenta localizar textos almacenados como UTF-16LE.
    """

    patron = re.compile(
        rb'(?:[\x20-\x7e\xC0-\xFF]\x00){2,}'
    )

    resultado = []

    for m in patron.finditer(data):

        try:

            texto = m.group().decode(
                "utf-16le",
                errors="ignore"
            ).strip()

        except Exception:

            continue

        if not texto:
            continue

        if len(texto) > 200:
            continue

        # ----------------------------------------------------
        # Limpiar texto inmediatamente
        # ----------------------------------------------------

        texto = limpiar_texto_qrp(texto)

        if not texto:
            continue

        # ----------------------------------------------------
        # NÚMEROS
        # ----------------------------------------------------

        if texto.isdigit():

            try:

                numero = int(texto)

            except ValueError:

                continue

            # Semanas 1-52
            if 1 <= numero <= 52:

                resultado.append(texto)
                continue

            # Recibos
            if 4 <= len(texto) <= 6:

                resultado.append(texto)
                continue

            # Matrículas
            if 6 <= len(texto) <= 12:

                resultado.append(texto)
                continue

            continue

        # ----------------------------------------------------
        # TEXTOS
        # ----------------------------------------------------

        if len(texto) >= 2:

            resultado.append(texto)

    return resultado


# ============================================================
# CONTAR COLEGIATURAS
# ============================================================

def contar_colegiaturas(concepto):

    texto = str(
        concepto
    ).strip().upper()

    if not texto:
        return 0

    for palabra in PALABRAS_COLEGIATURA:

        if palabra in texto:
            return 1

    texto = texto.replace(
        "SEMANA",
        ""
    )

    texto = texto.replace(
        ":",
        " "
    )

    texto = texto.replace(
        ";",
        " "
    )

    texto = texto.replace(
        "/",
        " "
    )

    numeros = re.findall(
        r"\d+",
        texto
    )

    if not numeros:
        return 0

    cantidad = 0

    for numero_texto in numeros:

        try:

            numero = int(
                numero_texto
            )

        except ValueError:

            continue

        if 1 <= numero <= 52:

            cantidad += 1

    return cantidad


# ============================================================
# CLASIFICAR
# ============================================================

def clasificar(concepto):

    texto = str(
        concepto
    ).strip().upper()

    if not texto:
        return "Sin concepto"

    if "EXTRAORDINARIO" in texto:
        return "Otros"

    for palabra in PALABRAS_COLEGIATURA:

        if palabra in texto:
            return "Colegiatura"

    if "EXAMEN" in texto:
        return "Examen"

    if "ORDINARIO" in texto:
        return "Examen"

    texto_normalizado = texto

    texto_normalizado = texto_normalizado.replace(
        "SEMANA",
        ""
    )

    texto_normalizado = texto_normalizado.replace(
        ":",
        " "
    )

    texto_normalizado = texto_normalizado.replace(
        ";",
        " "
    )

    texto_normalizado = texto_normalizado.replace(
        "/",
        " "
    )

    numeros = re.findall(
        r"\d+",
        texto_normalizado
    )

    if not numeros:
        return "Otros"

    numeros_validos = []

    for numero_texto in numeros:

        try:

            numero = int(
                numero_texto
            )

        except ValueError:

            continue

        if 1 <= numero <= 52:

            numeros_validos.append(
                numero
            )

        else:

            return "Otros"

    if numeros_validos:
        return "Colegiatura"

    return "Otros"


# ============================================================
# EXTRAER MOVIMIENTOS
# ============================================================

def extraer_movimientos(strings):

    movimientos = []

    i = 0

    def siguiente_valido(
        posicion,
        funcion,
        limite=30
    ):

        fin = min(
            posicion + limite,
            len(strings)
        )

        j = posicion

        while j < fin:

            valor = str(
                strings[j]
            ).strip()

            if funcion(valor):

                return j

            j += 1

        return None

    while i < len(strings):

        # ====================================================
        # RECIBO
        # ====================================================

        if not es_recibo(strings[i]):

            i += 1
            continue

        recibo = strings[i]

        # ====================================================
        # FECHA
        # ====================================================

        posicion_fecha = siguiente_valido(
            i + 1,
            es_fecha,
            20
        )

        if posicion_fecha is None:

            i += 1
            continue

        fecha = strings[
            posicion_fecha
        ]

        # ====================================================
        # IMPORTE
        # ====================================================

        posicion_importe = siguiente_valido(
            posicion_fecha + 1,
            es_importe,
            15
        )

        if posicion_importe is None:

            i += 1
            continue

        importe = strings[
            posicion_importe
        ]

        # ====================================================
        # RECARGO
        # ====================================================

        posicion_recargo = siguiente_valido(
            posicion_importe + 1,
            es_importe,
            15
        )

        if posicion_recargo is None:

            i += 1
            continue

        recargo = strings[
            posicion_recargo
        ]

        # ====================================================
        # USUARIO
        # ====================================================

        posicion_usuario = None

        j = posicion_recargo + 1

        limite_usuario = min(
            posicion_recargo + 15,
            len(strings)
        )

        while j < limite_usuario:

            valor = str(
                strings[j]
            ).strip()

            valor_limpio = limpiar_texto_qrp(
                valor
            )

            if (
                valor_limpio
                and not es_fecha(valor)
                and not es_hora(valor)
                and not es_importe(valor)
                and not valor.isdigit()
            ):

                posicion_usuario = j

                break

            j += 1

        if posicion_usuario is None:

            i += 1
            continue

        usuario = limpiar_texto_qrp(
            strings[posicion_usuario]
        )

        # ====================================================
        # BUSCAR TOTAL Y HORA
        # ====================================================

        posicion_hora = None
        posicion_total = None

        j = posicion_usuario + 1

        limite = min(
            posicion_usuario + 40,
            len(strings)
        )

        while j < limite:

            if es_hora(strings[j]):

                posicion_hora = j

                k = j - 1

                while k > posicion_usuario:

                    if es_importe(strings[k]):

                        posicion_total = k

                        break

                    k -= 1

                break

            j += 1

        if posicion_hora is None:

            i += 1
            continue

        if posicion_total is None:

            i += 1
            continue

        total = strings[
            posicion_total
        ]

        # ====================================================
        # CONCEPTO
        # ====================================================

        concepto_partes = strings[
            posicion_usuario + 1:
            posicion_total
        ]

        conceptos_texto = []
        semanas = []

        k = 0

        while k < len(concepto_partes):

            parte = limpiar_texto_qrp(
                concepto_partes[k]
            )

            if not parte:

                k += 1
                continue

            normalizado = (
                parte
                .upper()
                .replace(
                    "SEMANA",
                    ""
                )
                .replace(
                    ":",
                    ""
                )
                .replace(
                    " ",
                    ""
                )
            )

            # =================================================
            # VARIOS NÚMEROS
            # =================================================

            if re.fullmatch(
                r"\d+(,\d+)+",
                normalizado
            ):

                for numero_texto in normalizado.split(","):

                    try:

                        numero = int(
                            numero_texto
                        )

                    except ValueError:

                        continue

                    if 1 <= numero <= 52:

                        semanas.append(
                            str(numero)
                        )

                k += 1
                continue

            # =================================================
            # NÚMERO
            # =================================================

            if normalizado.isdigit():

                numero = int(
                    normalizado
                )

                if 10 <= numero <= 52:

                    semanas.append(
                        str(numero)
                    )

                    k += 1
                    continue

                if 1 <= numero <= 9:

                    candidato = None

                    if k + 1 < len(concepto_partes):

                        siguiente = limpiar_texto_qrp(
                            concepto_partes[k + 1]
                        )

                        siguiente_normalizado = (
                            siguiente
                            .upper()
                            .replace(
                                "SEMANA",
                                ""
                            )
                            .replace(
                                ":",
                                ""
                            )
                            .replace(
                                " ",
                                ""
                            )
                        )

                        if (
                            siguiente_normalizado.isdigit()
                            and len(
                                siguiente_normalizado
                            ) == 1
                        ):

                            candidato_texto = (
                                str(numero)
                                +
                                siguiente_normalizado
                            )

                            candidato = int(
                                candidato_texto
                            )

                    if (
                        candidato is not None
                        and
                        10 <= candidato <= 52
                    ):

                        semanas.append(
                            str(candidato)
                        )

                        k += 2
                        continue

                    semanas.append(
                        str(numero)
                    )

                    k += 1
                    continue

            # =================================================
            # TEXTO NORMAL
            # =================================================

            parte_limpia = limpiar_texto_qrp(
                parte
            )

            if len(parte_limpia) >= 2:

                conceptos_texto.append(
                    parte_limpia
                )

            k += 1

        # ====================================================
        # FILTRAR SEMANAS
        # ====================================================

        semanas_10_52 = []
        semanas_1_9 = []

        for semana in semanas:

            try:

                numero = int(
                    semana
                )

            except ValueError:

                continue

            if 10 <= numero <= 52:

                semanas_10_52.append(
                    str(numero)
                )

            elif 1 <= numero <= 9:

                semanas_1_9.append(
                    str(numero)
                )

        if semanas_10_52:

            semanas_finales = semanas_10_52

        else:

            semanas_finales = semanas_1_9

        concepto_partes_finales = []

        concepto_partes_finales.extend(
            conceptos_texto
        )

        concepto_partes_finales.extend(
            semanas_finales
        )

        concepto = limpiar_texto_qrp(
            " ".join(
                concepto_partes_finales
            )
        )

        # ====================================================
        # MOVIMIENTO
        # ====================================================

        movimiento = {

            "recibo":
                recibo,

            "fecha":
                fecha,

            "importe":
                convertir_decimal(
                    importe
                ),

            "recargo":
                convertir_decimal(
                    recargo
                ),

            "usuario":
                limpiar_texto_qrp(
                    usuario
                ),

            "concepto":
                concepto,

            "total":
                convertir_decimal(
                    total
                ),

            "hora":
                strings[
                    posicion_hora
                ]

        }

        movimientos.append(
            movimiento
        )

        i = posicion_hora + 1

    return movimientos


# ============================================================
# ANALIZAR
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

        concepto = movimiento[
            "concepto"
        ]

        categoria = clasificar(
            concepto
        )

        movimiento[
            "categoria"
        ] = categoria

        if categoria == "Colegiatura":

            cantidad = contar_colegiaturas(
                concepto
            )

            movimiento[
                "cantidad_colegiaturas"
            ] = cantidad

            resumen[
                "Colegiatura"
            ][
                "cantidad"
            ] += cantidad

        else:

            movimiento[
                "cantidad_colegiaturas"
            ] = 0

            resumen[
                categoria
            ][
                "cantidad"
            ] += 1

        resumen[
            categoria
        ][
            "importe"
        ] += movimiento[
            "total"
        ]

    return resumen


# ============================================================
# DECIMAL A JSON
# ============================================================

def decimal_a_json(obj):

    if isinstance(
        obj,
        Decimal
    ):

        return float(
            obj
        )

    if isinstance(
        obj,
        dict
    ):

        return {
            clave: decimal_a_json(
                valor
            )
            for clave, valor in obj.items()
        }

    if isinstance(
        obj,
        list
    ):

        return [
            decimal_a_json(
                valor
            )
            for valor in obj
        ]

    return obj


# ============================================================
# PROCESAR ARCHIVO
# ============================================================

def procesar_archivo(ruta):

    try:

        with open(
            ruta,
            "rb"
        ) as f:

            data = f.read()

        strings = extraer_strings_qrp(
            data
        )

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

        resumen = analizar(
            movimientos
        )

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
# MAIN
# ============================================================

if __name__ == "__main__":

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

    ruta = sys.argv[1]

    resultado = procesar_archivo(
        ruta
    )

    # ========================================================
    # IMPORTANTE:
    #
    # UTF-8 explícito para que PHP/Laravel reciba
    # correctamente el JSON.
    # ========================================================

    salida_json = json.dumps(
        resultado,
        ensure_ascii=False
    )

    sys.stdout.buffer.write(
        salida_json.encode(
            "utf-8"
        )
    )

    sys.stdout.buffer.write(
        b"\n"
    )

    if not resultado.get(
        "success",
        False
    ):

        sys.exit(1)

    sys.exit(0)