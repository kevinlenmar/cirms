<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| DEFAULT CI ROUTES
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'login/index';
$route['404_override'] = 'errors/page_not_found';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| #ERROR ROUTES FOR DIRECT ACCESSING
| -------------------------------------------------------------------------
*/

$route['cirms'] = "errors/page_not_found";
$route['cirms/(.*)'] = "errors/page_not_found";

$route['classroom'] = "errors/page_not_found";
$route['classroom/(.*)'] = "errors/page_not_found";

$route['cluster'] = "errors/page_not_found";
$route['cluster/(.*)'] = "errors/page_not_found";

$route['computer'] = "errors/page_not_found";
$route['computer/(.*)'] = "errors/page_not_found";

$route['report'] = "errors/page_not_found";
$route['report/(.*)'] = "errors/page_not_found";

$route['service_order'] = "errors/page_not_found";
$route['service_order/(.*)'] = "errors/page_not_found";

$route['user'] = "errors/page_not_found";
$route['user/(.*)'] = "errors/page_not_found";

/*
| -------------------------------------------------------------------------
| #ERROR ROUTES FOR FORBBIDEN METHODS
| -------------------------------------------------------------------------
*/

$route['403'] = "errors/access_denied";

/*
| -------------------------------------------------------------------------
| ROUTE FOR #MODAL
| -------------------------------------------------------------------------
*/

$route['modal/(:any)'] = 'ajax_cirms/modal/$1';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #LOGIN
| -------------------------------------------------------------------------
*/

$route['signout'] = 'login/signout';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #CIRMS
| -------------------------------------------------------------------------
*/

$route['dashboard'] = 'cirms/dashboard';
$route['pending-services'] = 'cirms/pending_services';
$route['ROF-pending'] = 'cirms/ROF_pending';
$route['tasks'] = 'cirms/tasks';
$route['help'] = 'cirms/help';
$route['about'] = 'cirms/about';
$route['terms'] = 'cirms/terms';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #USER
| -------------------------------------------------------------------------
*/

$route['new/user'] = 'user/user';
$route['manage/users'] = 'user/user_list';
$route['settings'] = 'user/settings';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #SERVICE ORDER
| -------------------------------------------------------------------------
*/

$route['new/service-order'] = 'service_order/service_order';
$route['manage/service-orders'] = 'service_order/service_order_list';

/*
| -------------------------------------------------------------------------
|  ROUTE FOR #CLASSROOM
| -------------------------------------------------------------------------
*/

$route['new/classroom'] = 'classroom/classroom';
$route['manage/classrooms'] = 'classroom/classroom_list';
$route['laboratory/(:any)'] = 'classroom/laboratory_ws/$1/$2';
$route['e-room'] = 'classroom/eroom_ws';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #CLUSTER
| -------------------------------------------------------------------------
*/

$route['new/cluster'] = 'cluster/cluster';
$route['manage/clusters'] = 'cluster/cluster_list';
$route['department/(:any)'] = 'cluster/department_ws/$1/$2';
$route['office/(:any)'] = 'cluster/office_ws/$1/$2';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #COMPUTER
| -------------------------------------------------------------------------
*/

$route['new/computer'] = 'computer/computer';
$route['manage/computers'] = 'computer/computer_list';
$route['new/resource'] = 'computer/computer_resource';
$route['manage/resources'] = 'computer/computer_resource_list';

/*
| -------------------------------------------------------------------------
| ROUTE FOR #REPORT
| -------------------------------------------------------------------------
*/

$route['service-order-report'] = 'report/service_order_report';
$route['software-reports'] = 'report/software_report';
$route['hardware-reports'] = 'report/hardware_report';
$route['classroom-report'] = 'report/classroom_report';
$route['cluster-report'] = 'report/cluster_report';
