@echo off
title Frontend Laravel - Control Escolar BTI
cd /d "D:\Proyectos\FrontICE\LARAVELBTI"
echo ========================================================
echo   Iniciando Frontend Laravel en http://127.0.0.1:8000
echo ========================================================
"C:\xampp\php\php.exe" artisan serve --port=8000
pause
