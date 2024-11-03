<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
$route['404_override'] = 'error';


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout';
$route['userListing'] = 'user/userListing';
$route['userListing/(:num)'] = "user/userListing/$1";
$route['addNew'] = "user/addNew";

$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";

$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

$route['qrcode'] = 'qrcode';

// $route['wmsLayout'] = 'wmsLayout';
// $route['wmsLayout/view']['GET'] = 'wmsLayout/view';
// $route['wmsLayout/Shelves']['GET'] = 'wmsLayout/Shelves';
$route['wmsLayout'] = 'wmsLayout/index';
$route['wmsLayout/view']['GET'] = 'wmsLayout/view';
$route['wmsLayout/Shelves']['GET'] = 'wmsLayout/Shelves';
$route['wmsLayout/store']['POST'] = 'wmsLayout/store';
$route['wmsLayout/delete/(:num)'] = 'wmsLayout/delete/$1';
$route['wmsLayout/update'] = 'wmsLayout/update';
$route['wmsLayout/generate'] = 'wmsLayout/generate';


$route['material/listMaterial']['GET'] = 'materials/index';
$route['material/store']['POST'] = 'materials/store';
$route['material/delete/(:num)'] = 'materials/delete/$1';
$route['material/update'] = 'materials/update';

$route['supplier/listSupplier']['GET'] = 'suppliers/index';
$route['supplier/store']['POST'] = 'suppliers/store';
$route['supplier/delete/(:num)'] = 'suppliers/delete/$1';
$route['supplier/update'] = 'suppliers/update';

$route['factory/listFactory'] = 'factories';
$route['factory/listFactory/(:num)'] = "factories/index/$1";
$route['factory/store']['POST'] = 'factories/store';
$route['factory/delete/(:num)'] = 'factories/delete/$1';
$route['factory/update'] = 'factories/update';


$route['goods-receipts'] = 'receipts';
$route['goods-receipts/(:num)'] = "receipts/index/$1";
$route['goods-receipts/detail/(:num)'] = 'receipts/detail/$1';
$route['mom-reports'] = 'receipts/mom_report';
$route['mom-reports/(:num)'] = "receipts/mom_report/$1";
$route['mom-reports/detail/(:num)'] = 'receipts/mom_detail/$1';
$route['create-goods-receipt']['GET'] = 'receipts/create_goods_receipt';
$route['create-goods-receipt']['POST'] = 'receipts/create_new_goods_receipt';
$route['receipts/processForm/(:num)']['POST'] = 'receipts/processForm/$1';
$route['receipts/update_receipt_details']['POST'] = 'receipts/update_receipt_details';
$route['add-material']['POST'] = 'receipts/add_material';
$route['edit-material']['POST'] = 'receipts/edit_material';
$route['rating-material']['POST'] = 'receipts/rating_material';
$route['delete-material']['POST'] = 'receipts/delete_material';
$route['export-QRCode']['POST'] = 'receipts/generate';

$route['inventory'] = 'inventory';
$route['inventory/detail/(:num)'] = 'inventory/detail/$1';

$route['stock-report'] = 'inventory/stock_report';

$route['goods-issue'] = 'issue';
$route['goods-issue/(:num)'] = "issue/index/$1";
$route['goods-issue/detail/(:num)'] = 'issue/detail/$1';
$route['create-goods-issue']['GET'] = 'issue/create_goods_issue';
$route['create-goods-issue']['POST'] = 'issue/create_new_goods_issue';
$route['add-material-issue']['POST'] = 'issue/add_material';
$route['edit-material-issue']['POST'] = 'issue/edit_material';
$route['delete-material-issue']['POST'] = 'issue/delete_material';
$route['issue/processForm/(:num)']['POST'] = 'issue/processForm/$1';
$route['receiving-request'] = 'issue/receiving_request';
$route['receiving-request/(:num)'] = "issue/receiving_request/$1";
$route['receiving-request/detail/(:num)'] = 'issue/receiving_request_details/$1';
$route['scan-qrcode']['POST'] = 'issue/qrcode';

$route['stocktaking'] = 'stocktaking';
$route['stocktaking/(:num)'] = "stocktaking/index/$1";
$route['stocktaking/detail/(:num)'] = 'stocktaking/detail/$1';
$route['create-request-inventory']['GET'] = 'stocktaking/create_request_inventory';
$route['create-request-inventory']['POST'] = 'stocktaking/create_new_request_inventory';
$route['add-material-inventory']['POST'] = 'stocktaking/add_material';
$route['delete-material-inventory']['POST'] = 'stocktaking/delete_material';
$route['stocktaking/processForm/(:num)']['POST'] = 'stocktaking/processForm/$1';
$route['my-stocktaking'] = 'stocktaking/stocktaking_inventory';
$route['my-stocktaking/(:num)'] = "stocktaking/stocktaking_inventory/$1";
$route['my-stocktaking/detail/(:num)'] = 'stocktaking/detail/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */