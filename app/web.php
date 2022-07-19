<?php
require_once 'config.tpl';
require_once '../framework/Helper/helpers.php';

use framework\Routing\Route;

session_start();
/*********************************Authorization******************************************/
    Route::get("/login","Authorization.ViewLogin");
    Route::post("/login","Authorization.Login");
    Route::middleware("/","Authorization.isAuthorize");

/*********************************CreateQuote Page***********************************/
/***********************************Get Request***********************************/

    Route::get("/", "createquote.page");
    /***********************************TRAVELLER Get Request***********************************/
    Route::get("/traveller/?id", "TravellerDetails.page");
    /**********************************************************************/
    Route::get("/createquote", "createquote.page");
    Route::get("/getQuoteform/?tripid", "createquote.getQuoteform");
    Route::get("/getAlredyGivenQuote/?srch", "createquote.getAlredyGivenQuote");
    Route::get("/getfilledQuoteform/?quoteid/?tripid", "createquote.getfiledQuoteform");

/***********************************Post Request***********************************/

    Route::post("/createquote/quote", "createquote.quote");
    Route::post("/giveQuotation/","give_quotationController._give");
    Route::post("/AssignAgent/customerQuery/?cq_id/Agent/?agent_id","createquote.AssignAgent");

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

    Route::delete("/DiscardCreateQuote/?id","createquote.discardQuote");

/*********************************Quotationfollowup Page***********************************/
/***********************************Get Request***********************************/
    Route::get("/quotationFollowUp","QuotationFollowUp.pagee");
    Route::get("/quotationFollowUp/?tab","QuotationFollowUp.pagee");
    Route::get("/traveller/sendQuotationonTravellerPageForQuoteid/?quoteid", "QuotationFollowUp.getQuoteforQuoteid");

/***********************************Post Request***********************************/
    Route::post("/quotationFollowUp/Card", "QuotationFollowUp.quotationFollowupCard");

    /***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

/*********************************note***********************************/
/***********************************Get Request***********************************/


/***********************************Post Request***********************************/
Route::post("/Note/?tripid", "Note.Save");
/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/


/*********************************Vouchersandpayemnts Page***********************************/
/***********************************Get Request***********************************/
Route::get("/vouchersAndPayments" ,"VouchersAndPayments.page");

/***********************************Post Request***********************************/

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

/*********************************During Stay Page***********************************/
/***********************************Get Request***********************************/
Route::get("/duringStay","DuringStay.page");

/***********************************Post Request***********************************/
Route::post("/DuringStay/Card", "DuringStay.card");

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

/*********************************Post stay Page***********************************/
/***********************************Get Request***********************************/
Route::get("/postStay","PostStay.page");

/***********************************Post Request***********************************/
Route::post("/PostStay/Card", "PostStay.card");

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

/*********************************SpocManagement Page***********************************/
/***********************************Get Request***********************************/
Route::get("/spocManagement","SpocManagement.page");

/***********************************Post Request***********************************/

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

/*********************************CreateQuote Page***********************************/
/***********************************Get Request***********************************/
Route::get("/spocManagement", function () {

});

/***********************************Post Request***********************************/

/***********************************Update Request***********************************/

/***********************************Delete Request***********************************/

include VIEW_DIR."404.php";