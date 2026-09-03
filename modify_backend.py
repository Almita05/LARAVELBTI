import re

file_path = r"D:\Proyectos\2 Mi controlEscolar\app\services\periodos_academico.py"
print("Reading file...")
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Let's locate the target function logic
target_func = """    @staticmethod
    def actualizarTodosLosGrupos():
        conexion = get_connection()
        cursor = conexion.cursor(pymysql.cursors.DictCursor)
        try:
            # ÚNICAMENTE actualizamos los grupos que están activos
            cursor.execute("SELECT id FROM tb_grupos WHERE statusGrupo = 'ACTIVO'")
            grupos = cursor.fetchall()
        finally:
            cursor.close()
            conexion.close()

        actualizados = 0
        for g in grupos:
            if PeriodoAcademicoService.actualizarNivelGrupo(g["id"]):
                actualizados += 1
        return actualizados"""

replacement_func = """    @staticmethod
    def actualizarTodosLosGrupos():
        conexion = get_connection()
        cursor = conexion.cursor(pymysql.cursors.DictCursor)
        try:
            # ÚNICAMENTE actualizamos los grupos que están activos (obtenemos clave para filtrar BTI)
            cursor.execute("SELECT id, clave FROM tb_grupos WHERE statusGrupo = 'ACTIVO'")
            grupos = cursor.fetchall()
        finally:
            cursor.close()
            conexion.close()

        actualizados = 0
        for g in grupos:
            clave = g.get("clave") or ""
            # Los grupos de BTI no deben actualizar su semestre automáticamente (se respeta el manual)
            if clave.upper().startswith("BTI"):
                continue
            if PeriodoAcademicoService.actualizarNivelGrupo(g["id"]):
                actualizados += 1
        return actualizados"""

if target_func in content:
    print("Found target function exactly! Replacing...")
    new_content = content.replace(target_func, replacement_func)
    with open(file_path, "w", encoding="utf-8") as f:
        f.write(new_content)
    print("Replacement successful!")
else:
    print("Exact target function match not found. Trying flexible regex replace...")
    # Flexible match in case of minor whitespace/line-ending differences or comment encoding variations
    pattern = r"def actualizarTodosLosGrupos\(\):.*?\n\s+return actualizados"
    match = re.search(pattern, content, re.DOTALL)
    if match:
        print("Found function via regex!")
        # Let's inspect what it matched
        print("Matched code:")
        print(match.group(0))
        
        # We can construct the new function replacement
        # Let's find the inner block of code and replace it safely
        # To be safe, let's replace the whole function block
        # We'll match everything from `def actualizarTodosLosGrupos():` to the end of the method
        # Let's define the pattern more clearly:
        pattern_full = r"    @staticmethod\s+def actualizarTodosLosGrupos\(\):.*?return actualizados"
        new_content, count = re.subn(pattern_full, replacement_func, content, flags=re.DOTALL)
        if count > 0:
            with open(file_path, "w", encoding="utf-8") as f:
                f.write(new_content)
            print("Regex replacement successful!")
        else:
            print("Regex replacement failed.")
    else:
        print("Could not match the function at all.")
