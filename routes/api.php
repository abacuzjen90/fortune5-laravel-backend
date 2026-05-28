<?php


use App\Http\Controllers\EventController;
use App\Http\Controllers\InventoryBookletController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryUnitBigController;
use App\Http\Controllers\InventoryUnitSmallController;
use App\Http\Controllers\MasterlistController;
use App\Http\Controllers\BranchtypeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SpecialItemController;
use App\Http\Controllers\SubdepartmentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\MenuAccessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AirChargeController;
use App\Http\Controllers\ChargetoController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\WaybillHeaderController;
use App\Http\Controllers\WaybillDetailsController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\StockHeaderController;
use App\Http\Controllers\StockDetailsController;
use App\Http\Controllers\IssuanceHeaderController;
use App\Http\Controllers\IssuanceDetailsController;
use App\Http\Controllers\BankCalendarController;
use App\Http\Controllers\GeneralSalesCashCountController;
use App\Http\Controllers\GeneralSalesPettyCashController;
use App\Http\Controllers\GeneralSalesMonitoringController;
use App\Http\Controllers\RentalSpaceController;
use App\Http\Controllers\RentalTenantController;
use App\Http\Controllers\RentalLeaseController;
use App\Http\Controllers\DateBlockedController;
use App\Http\Controllers\DateLimitController;
use App\Http\Controllers\RentalExpensesController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LeaveController;

use App\Http\Controllers\PayrollEmployeeController;
use App\Http\Controllers\PayrollController;

use App\Http\Controllers\SupplierController;

use App\Http\Controllers\AccountHeaderController;
use App\Http\Controllers\AccountSubController;
use App\Http\Controllers\AccountTitleController;
// use App\Http\Controllers\PayableController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->post('/upload-image-item', [InventoryItemController::class, 'uploadImageItem']);
    Route::middleware('auth:sanctum')->post('/upload-image-user', [AuthController::class, 'uploadImageUser']);

    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('rentalspace', RentalSpaceController::class);
    Route::apiResource('rentaltenant', RentalTenantController::class);
    Route::apiResource('rentallease', RentalLeaseController::class);
    Route::apiResource('dateblocked', DateBlockedController::class);
    Route::apiResource('datelimit', DateLimitController::class);
    Route::apiResource('rentalexpenses', RentalExpensesController::class);
    Route::apiResource('generalsalespettycash', GeneralSalesPettyCashController::class);
    Route::apiResource('generalsalescashcount', GeneralSalesCashCountController::class);
    Route::apiResource('generalsalesmonitoring', GeneralSalesMonitoringController::class);
    Route::apiResource('event', EventController::class);
    Route::apiResource('bankcalendar', BankCalendarController::class);
    Route::apiResource('inventoryitem', InventoryItemController::class);
    Route::apiResource('stockheader', StockHeaderController::class);
    Route::apiResource('stockdetails', StockDetailsController::class);
    Route::apiResource('inventoryunitbig', InventoryUnitBigController::class);
    Route::apiResource('inventoryunitsmall', InventoryUnitSmallController::class);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('employee', AuthController::class);
    Route::apiResource('masterlists', MasterlistController::class);
    Route::apiResource('branchtype', BranchtypeController::class);
    Route::apiResource('designation', DesignationController::class);
    Route::apiResource('department', DepartmentController::class);
    Route::apiResource('subdepartment', SubdepartmentController::class);
    Route::apiResource('menu', MenuController::class);
    Route::apiResource('submenu', SubmenuController::class);
    Route::apiResource('emp_menu_access', MenuAccessController::class);
    Route::apiResource('branch', BranchController::class);
    Route::apiResource('aircharge', AirchargeController::class);
    Route::apiResource('specialitem', SpecialItemController::class);
    Route::apiResource('chargeto', ChargetoController::class);
    Route::apiResource('destination', DestinationController::class);
    Route::apiResource('inventorybooklet', InventoryBookletController::class);
    Route::apiResource('waybillheader', WaybillHeaderController::class);
    Route::apiResource('waybilldetails', WaybillDetailsController::class);
    Route::apiResource('delivery', DeliveryController::class);
    Route::apiResource('issuanceheader', IssuanceHeaderController::class);
    Route::apiResource('issuancedetails', IssuanceDetailsController::class);



Route::middleware('auth:sanctum')->get('/generalsalespettycash', [GeneralSalesPettyCashController::class, 'index']);
Route::middleware('auth:sanctum')->get('/generalsalescashcount', [GeneralSalesCashCountController::class, 'index']);
Route::middleware('auth:sanctum')->get('/generalsalesmonitoring', [GeneralSalesMonitoringController::class, 'index']);


// note: import authController


// Import Excel
Route::post('/import-biometric', [PayrollEmployeeController::class, 'import']);
// FILTERING DATATABLES
Route::get('/payroll/filter', [PayrollController::class, 'filterPayroll']);
Route::get('/employees', [PayrollController::class, 'getEmployees']);
Route::put('/payroll/{id}', [PayrollController::class, 'updatePayroll']);
// CALCULATE SSS/EE AND SAVE
Route::post('/payroll/calculate-sssee', [PayrollController::class, 'calculateSSSEE']);
Route::post('/payroll/save-all-sssee', [PayrollController::class, 'saveAllSSSEE']);
// CALCULATE PAG-IBIG PREM
Route::post('/payroll/calculate-pagibig', [PayrollController::class, 'calculatePagibig']);
Route::post('/payroll/save-all-pagibig', [PayrollController::class, 'savePagibig']);
// LOANS
Route::post('/payroll/calculate-loans', [PayrollController::class, 'calculateLoans']);
Route::post('/payroll/save-all-loans', [PayrollController::class, 'saveLoans']);
// CASH LOAN AND EMP LIAB
Route::post('payroll/calculate-cash-loan-emp-liab', [PayrollController::class, 'calculateCashLoanAndEmpLiab']);
Route::post('payroll/save-all-cash-loan-emp-liab', [PayrollController::class, 'saveAllCashLoanAndEmpLiab']);
// UPDATE LOANS SUCH AS CASH LOAN AMOUNT , EMP LIAB AMOUNT
Route::put('/payroll/{id}', [PayrollController::class, 'update']);
// TAX
Route::post('/payroll/calculate-tax', [PayrollController::class, 'calculateTax']);
Route::post('/payroll/save-all-tax', [PayrollController::class, 'saveTax']);
//END

//supplier entry
Route::get('/suppliers', [SupplierController::class, 'index']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::put('/suppliers/{id}', [SupplierController::class, 'update']);
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);



//chart of accounts header
Route::apiResource('account-headers', AccountHeaderController::class);
Route::prefix('account-subs')->group(function () {
    Route::get('/', [AccountSubController::class, 'index']);
    Route::post('/', [AccountSubController::class, 'store']);
    Route::get('/{id}', [AccountSubController::class, 'show']);
    Route::put('/{id}', [AccountSubController::class, 'update']);
    Route::delete('/{id}', [AccountSubController::class, 'destroy']);
});

//chart of accounts title
Route::apiResource('account-titles', AccountTitleController::class);

Route::get('/branchdata', [BranchController::class, 'branchdata']);
Route::get('/branchagency', [BranchController::class, 'branchagency']);
Route::get('/deptdata', [DepartmentController::class, 'deptdata']);
Route::get('/menudata', [MenuController::class, 'menudata']);
Route::post('/uploadimage', [MasterlistController::class, 'uploadimage']);
Route::get('/showimage/{id}', [MasterlistController::class, 'showimage']);
Route::get('/showprofileimage/{id}', [MasterlistController::class, 'showprofileimage']);
Route::put('/toprofileimage/{id}', [MasterlistController::class, 'toprofileimage']);
Route::put('/unprofileimage/{id}', [MasterlistController::class, 'unprofileimage']);
Route::delete('/deleteimage/{id}', [MasterlistController::class, 'deleteimage']);
Route::put('/del_menu_access/{id}', [MenuAccessController::class, 'del_menu_access']);
Route::get('/getempmenu/{id}', [MenuAccessController::class, 'getempmenu']);
Route::get('/customerbybranch', [CustomerController::class, 'getCustomersByBranch']);
Route::get('/getverifycustomer', [CustomerController::class, 'getVerifyCustomer']);
Route::get('/customerbyprepaid', [CustomerController::class, 'getCustomersByPrepaid']);
Route::put('/updateblackliststatus/{id}', [CustomerController::class, 'updateBlacklistStatus']);
Route::put('/updatestatus/{id}', [CustomerController::class, 'updateStatus']);
Route::get('/getspecialitem/{id}', [SpecialItemController::class, 'getSpecialItem']);
Route::get('/getcustomerconsignee/{id}', [CustomerController::class, 'getCustomerConsignee']);
Route::get('/getcustomershipper/{id}', [CustomerController::class, 'getCustomerShipper']);
Route::post('/addcustomerconsignee', [CustomerController::class, 'addCustomerConsignee']);
Route::post('/addcustomershipper', [CustomerController::class, 'addCustomerShipper']);
Route::post('/addcustomerwaybillshipper', [CustomerController::class, 'addCustomerWaybillShipper']);
Route::post('/addcustomerwaybillconsignee', [CustomerController::class, 'addCustomerWaybillConsignee']);
Route::put('/updatecustomerconsignee/{id}', [CustomerController::class, 'updateCustomerConsignee']);
Route::put('/updatecustomershipper/{id}', [CustomerController::class, 'updateCustomerShipper']);
Route::put('/updatecustomerrates/{id}', [CustomerController::class, 'updateCustomerRates']);
Route::put('/updateverifycustomer', [CustomerController::class, 'updateVerifyCustomer']);
Route::put('/deleteverifycustomer', [CustomerController::class, 'deleteVerifyCustomer']);
Route::get('/chargetobranch/{branch?}', [ChargetoController::class, 'chargeToBranch']);
Route::get('/destinationrates/{desti?}', [DestinationController::class, 'destinationRates']);

Route::get('/getwaybillno/{id?}', [WaybillHeaderController::class, 'getWaybillNo']);
Route::get('/getwaybillshipper/{id?}', [CustomerController::class, 'getWaybillShipper']);
Route::get('/getwaybillconsignee/{id?}', [CustomerController::class, 'getWaybillConsignee']);
Route::get('/getshipperconsignee/{id?}', [CustomerController::class, 'getShipperConsignee']);
Route::get('/getconsigneeshipper/{id?}', [CustomerController::class, 'getConsigneeShipper']);
Route::post('/saveadvalorem', [WaybillDetailsController::class, 'saveAdvalorem']);
Route::post('/savepercbm', [WaybillDetailsController::class, 'savePerCBM']);
Route::post('/saveperkilo', [WaybillDetailsController::class, 'savePerKilo']);
Route::post('/savespecialitem', [WaybillDetailsController::class, 'saveSpecialItem']);
Route::get('/getcustomerspecialitem', [SpecialItemController::class, 'getCustSpecialItem']);

Route::get('/getstockdetails/{id?}', [StockDetailsController::class, 'getStockDetails']);
Route::get('/getproductlist', [StockDetailsController::class, 'getProductList']);
Route::get('/getproductlistdashboard', [StockDetailsController::class, 'getProductListDashboard']);
Route::get('/getproductsearch', [StockDetailsController::class, 'getProductSearch']);
Route::get('/getissuancelist', [IssuanceDetailsController::class, 'getIssuanceList']);
Route::get('/getissuancelistdashboard', [IssuanceDetailsController::class, 'getIssuanceListDashboard']);
Route::get('/getissuancedetails/{id?}', [IssuanceHeaderController::class, 'getIssuanceDetails']);
Route::put('/issuanceheader/{id}', [IssuanceHeaderController::class, 'update']);
Route::post('/addinventoryitem', [InventoryItemController::class, 'addInventoryItem']);
Route::post('/updateinventoryitem/{id}', [InventoryItemController::class, 'updateInventoryItem']);
Route::get('/getdashboardstock', [StockDetailsController::class, 'getDashboardStock']);
Route::put('/bankcalendar/{id}', [BankCalendarController::class, 'update']);
Route::delete('/bankcalendar/{id}', [BankCalendarController::class, 'destroy']);
Route::get('/bankcheckreplaced/{id}', [BankCalendarController::class, 'bankCheckReplaced']);
Route::get('/bankcheckreplacedid/{id}', [BankCalendarController::class, 'bankCheckReplacedId']);
Route::post('/savereplacedcheck', [BankCalendarController::class, 'storeCheckReplaced']);
Route::get('/getbankreport', [BankCalendarController::class, 'getBankReport']);
Route::get('/getbankresultdate', [BankCalendarController::class, 'getBankResultDate']);
Route::get('/generalsalespettycashhistory', [GeneralSalesPettyCashController::class, 'getPettyCashHistory']);
Route::get('/generalsalespettycashencoder', [GeneralSalesPettyCashController::class, 'getPettyCashEncoder']);
Route::get('/generalsalescashcounthistory', [GeneralSalesCashCountController::class, 'getCashCountHistory']);
Route::get('/generalsalescashcountencoder', [GeneralSalesCashCountController::class, 'getCashCountEncoder']);
Route::get('/generalsalesmonitoringhistory', [GeneralSalesMonitoringController::class, 'getMonitoringHistory']);
Route::get('/generalsalesmonitoringencoder', [GeneralSalesMonitoringController::class, 'getMonitoringEncoder']);
Route::get('/transactionhistory', [GeneralSalesMonitoringController::class, 'transactionSearchResult']);
Route::get('/rentaltransactionhistory', [RentalLeaseController::class, 'RentalTransactionResult']);

// For updating issuance details
Route::put('/issuancedetails/{id}', [IssuanceDetailsController::class, 'update']);
//Route::delete('/deleteacc/{id}', [AuthController::class, 'destroy']);

// Leave Entry
Route::get('/employees', [LeaveController::class, 'employee_index']);
Route::get('/leave_records', [LeaveController::class, 'leave_record']);
Route::get('/deleted_records', [LeaveController::class, 'deleted_leave_record']);
Route::post('/leave_details', [LeaveController::class, 'leave_index']);
Route::post('/leave_records/delete', [LeaveController::class, 'destroy']);
Route::post('/leave_records/restore', [LeaveController::class, 'restore']);
Route::post('/leave_records/approve', [LeaveController::class, 'approve']);
Route::post('/leave_records/disapprove', [LeaveController::class, 'disapprove']);
Route::post('/leave', [LeaveController::class, 'leave']);

// Route::get('/', function (){
//     return 'API';
// });
});
