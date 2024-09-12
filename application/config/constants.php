<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//MY CUSTOM CONSTANTS
defined('OPTION_DEFAULT_TEXT') or define('OPTION_DEFAULT_TEXT', "--- seleccionar ---");
defined('OPTION_DEFAULT_ALL') or define('OPTION_DEFAULT_ALL', "--- Todos ---");
defined('RECORD_STATUS_ACTIVE') or define('RECORD_STATUS_ACTIVE', 1);
defined('RECORD_STATUS_INACTIVE') or define('RECORD_STATUS_INACTIVE', 0);
defined('ID_CONST_REG_STATUS') or define('ID_CONST_REG_STATUS', "11");
defined('ID_CONST_REG_MONEY') or define('ID_CONST_REG_MONEY', "31");
defined('ID_CONST_REG_PPAGO') or define('ID_CONST_REG_PPAGO', "28");
defined('ID_CONST_REG_FPAGO') or define('ID_CONST_REG_FPAGO', "30");
defined('ID_CONST_REG_TDOC') or define('ID_CONST_REG_TDOC', "4");
defined('ID_CONST_REG_TPROVEEDOR') or define('ID_CONST_REG_TPROVEEDOR', "33");
defined('ID_CONST_REG_TRUBRO') or define('ID_CONST_REG_TRUBRO', "34");
defined('ID_CONST_REG_TCOMPRAB') or define('ID_CONST_REG_TCOMPRAB', "35");

defined('ROLE_ADMIN') or define('ROLE_ADMIN', "administrador");
defined('USERNAME_ADMIN') or define('USERNAME_ADMIN', "admin");

defined('RECORD_STATUS_ACTIVE_TEXT') or define('RECORD_STATUS_ACTIVE_TEXT', "Activo");
defined('RECORD_STATUS_INACTIVE_TEXT') or define('RECORD_STATUS_INACTIVE_TEXT', "Inactivo");

defined('UPLOAD_PATH_DOCUMENTS') or define('UPLOAD_PATH_DOCUMENTS', "./assets/upload/documents/");
defined('UPLOAD_PATH_ERASER') or define('UPLOAD_PATH_ERASER', "./assets/upload/eraser/");
defined('UPLOAD_PATH_USERS') or define('UPLOAD_PATH_USERS', "./assets/upload/images/users/");
defined('UPLOAD_PATH_PERSON_FORMAT') or define('UPLOAD_PATH_PERSON_FORMAT', "./assets/upload/formatoPersonInsert/formato_personales.xlsx");
defined('UPLOAD_PATH_UNDMEDIDA_FORMAT') or define('UPLOAD_PATH_UNDMEDIDA_FORMAT', "./assets/upload/excelUndMedida/excel_undmedida.xlsx");
defined('UPLOAD_DEFAULT_NOT_IMAGE') or define('UPLOAD_DEFAULT_NOT_IMAGE', "no_image.jpg");

defined('URL_COMP_ERASER') or define('URL_COMP_ERASER', "http://epos.test/assets/upload/documents/");

defined('VALUE_AL_DIA') or define('VALUE_AL_DIA', "AL DÍA");
defined('VALUE_ATRASADO') or define('VALUE_ATRASADO', "ATRASADO");
defined('VALUE_EN_PROCESO') or define('VALUE_EN_PROCESO', "EN PROCESO");

defined('VALUE_ESTPAGO_PEND') or define('VALUE_ESTPAGO_PEND', "PENDIENTE");
defined('VALUE_ESTPAGO_ACEP') or define('VALUE_ESTPAGO_ACEP', "ACEPTADO");
defined('VALUE_ESTPAGO_RECH') or define('VALUE_ESTPAGO_RECH', "RECHAZADO");

defined('SYM_PEN') or define('SYM_PEN', "S/.");
defined('SYM_USD') or define('SYM_USD', "$/.");

defined('CONST_COD_MATERIALES') or define('CONST_COD_MATERIALES', "MAT");
defined('CONST_COD_HERRAMIENTAS') or define('CONST_COD_HERRAMIENTAS', "HER");
defined('CONST_COD_EQUIPOS') or define('CONST_COD_EQUIPOS', "EQP");

defined('ERASER_OK') or define('ERASER_OK', 1);
defined('ERASER_REMOVE') or define('ERASER_REMOVE', 0);

