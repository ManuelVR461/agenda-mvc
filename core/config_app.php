<?php

# ARCHIVO CON PARÁMETROS GLOBALES DE LA APLICACIÓN.
##########################################################################################
# IMPORTANTE                                                                             #
#Nombre del host (Ej: www.dominio.cl). Si no hay dominio asociado debe quedar en blanco. #
$HOST_NAME = 'localhost';                                                       #
# IMPORTANTE                                                                             #
##########################################################################################
##########################################################
# Nombre de la aplicación, controller y action de inicio #
# varía según la aplicación                              #
##########################################################
#Nombre de la aplicación
define('APP_NAME', 'Sistema de Agenda');
#Controlador por defecto
define('INDEX_CONTROLLER', 'agenda');
#Acción por defecto
define('INDEX_ACTION', 'agendamiento');
#Nombre de la base de Datos
define('DB_DEFAULT', 'agenda');