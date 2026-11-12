-- ==============================================================================
-- FASE 1: MODELO DE DATOS Y ESTRUCTURA PARA MODALIDAD ONLINE
-- Sistema de Control Escolar BTI / BGNE
-- ==============================================================================

-- 1. Agregar columnas para Moodle y asignación de bloques en tb_materias
ALTER TABLE tb_materias 
    ADD COLUMN id_curso_moodle INT NULL DEFAULT NULL COMMENT 'ID del curso correspondiente en Moodle',
    ADD COLUMN mes_bloque TINYINT NULL DEFAULT NULL COMMENT 'Mes o bloque en modalidad online (1, 2, 3)',
    ADD COLUMN par_materia_orden TINYINT NULL DEFAULT 1 COMMENT '1 = Primera materia del mes, 2 = Segunda materia del mes';

-- 2. Agregar columnas completas de Modalidad Online en tb_alumnos
ALTER TABLE tb_alumnos 
    ADD COLUMN modalidad_estudio ENUM('PRESENCIAL', 'ONLINE') NOT NULL DEFAULT 'PRESENCIAL',
    ADD COLUMN dia_pago ENUM('LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO') NOT NULL DEFAULT 'SABADO',
    ADD COLUMN moodle_user_id INT NULL DEFAULT NULL,
    ADD COLUMN moodle_username VARCHAR(100) NULL DEFAULT NULL,
    ADD COLUMN moodle_password VARCHAR(100) NULL DEFAULT NULL,
    ADD COLUMN semana_actual_pagada INT NOT NULL DEFAULT 0,
    ADD COLUMN trimestre_inicio_online INT NOT NULL DEFAULT 1 COMMENT 'Trimestre en que inició modalidad online',
    ADD COLUMN semanas_totales_pagadas INT NOT NULL DEFAULT 0 COMMENT 'Total acumulado de semanas pagadas',
    ADD COLUMN fecha_proximo_pago DATE NULL DEFAULT NULL;

-- 3. Agregar columnas de detalle en tb_alumno_pagos
ALTER TABLE tb_alumno_pagos 
    ADD COLUMN semanas_pagadas INT NOT NULL DEFAULT 1 COMMENT 'Cantidad de semanas pagadas en esta exhibicion',
    ADD COLUMN id_nivel_academico INT NULL COMMENT 'Trimestre o nivel academico cubierto',
    ADD COLUMN mes_bloque INT NULL COMMENT 'Mes cubierto (1, 2, 3)',
    ADD COLUMN sincronizado_moodle TINYINT(1) DEFAULT 0,
    ADD COLUMN fecha_sincronizacion_moodle DATETIME NULL;

-- 4. Crear tabla de control granular de actividades/semanas por alumno
CREATE TABLE IF NOT EXISTS tb_alumno_actividades_online (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_materia INT NOT NULL,
    id_nivel_academico INT NOT NULL,
    mes_bloque TINYINT NOT NULL COMMENT 'Mes del bloque: 1, 2 o 3',
    semana TINYINT NOT NULL COMMENT 'Semana de la materia: 1, 2, 3 o 4',
    id_pago INT NULL COMMENT 'ID del pago que liberó esta semana',
    fecha_liberacion DATETIME NULL COMMENT 'Fecha y hora en que se liberó la semana (al registrar el pago)',
    fecha_entrega DATETIME NULL COMMENT 'Fecha y hora en que el alumno subió la tarea en Moodle',
    estatus ENUM('BLOQUEADA', 'LIBERADA', 'ENTREGADA', 'CALIFICADA') DEFAULT 'BLOQUEADA',
    moodle_submission_id BIGINT NULL COMMENT 'ID de la entrega de la tarea en Moodle',
    calificacion DECIMAL(5,2) NULL COMMENT 'Calificación obtenida en Moodle',
    observaciones VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_alumno_materia_semana (id_alumno, id_materia, semana),
    INDEX idx_alumno_nivel (id_alumno, id_nivel_academico),
    INDEX idx_pago (id_pago),
    CONSTRAINT fk_actividades_alumno FOREIGN KEY (id_alumno) REFERENCES tb_alumnos(idAlumno) ON DELETE CASCADE,
    CONSTRAINT fk_actividades_materia FOREIGN KEY (id_materia) REFERENCES tb_materias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- 5. MAPEO DE MESES Y MATERIAS (BGNE - CCT 3)
-- ==============================================================================

-- --- PRIMER TRIMESTRE ---
-- MES I: Informática I (10) y Geografía (9) / Intro Ciencias Sociales (12)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 10;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id IN (9, 12);
-- MES II: Lectura y Redacción I (13) y Química I (11)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 13;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 11;
-- MES III: Matemáticas I (8) e Inglés I (14)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 8;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 14;

-- --- SEGUNDO TRIMESTRE ---
-- MES I: Informática II (16) y Lectura y Redacción II (19)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 16;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id = 19;
-- MES II: Química II (17) e Historia de México I (18)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 17;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 18;
-- MES III: Matemáticas II (15) e Inglés II (20)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 15;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 20;

-- --- TERCER TRIMESTRE ---
-- MES I: Informática III (22) y Biología I (24)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 22;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id = 24;
-- MES II: Historia de México II (25) e Inglés III (26)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 25;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 26;
-- MES III: Matemáticas III (21) y Física I (23)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 21;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 23;

-- --- CUARTO TRIMESTRE ---
-- MES I: Informática IV (28) y Biología II (30)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 28;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id = 30;
-- MES II: Estructura Socioeconómica de México (31) e Inglés IV (32)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 31;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 32;
-- MES III: Matemáticas IV (27) y Física II (29)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 27;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 29;

-- --- QUINTO TRIMESTRE ---
-- MES I: Informática V (34) e Historia Universal Contemporánea (36)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 34;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id = 36;
-- MES II: Metodología de la Investigación (37) e Inglés V (38)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 37;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 38;
-- MES III: Matemáticas V (33) y Literatura I (35)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 33;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 35;

-- --- SEXTO TRIMESTRE ---
-- MES I: Informática VI (40) y Ecología y Medio Ambiente (42)
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 1 WHERE id = 40;
UPDATE tb_materias SET mes_bloque = 1, par_materia_orden = 2 WHERE id = 42;
-- MES II: Filosofía (43) y Contabilidad I (44)
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 1 WHERE id = 43;
UPDATE tb_materias SET mes_bloque = 2, par_materia_orden = 2 WHERE id = 44;
-- MES III: Matemáticas VI (39) y Literatura II (41)
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 1 WHERE id = 39;
UPDATE tb_materias SET mes_bloque = 3, par_materia_orden = 2 WHERE id = 41;
