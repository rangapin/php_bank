<?php

	require("SubClasses.php");

	$Account1 = new ISA;

	$Account1->APR = 5.0;
	$Account1->SortCode = "20-20-20";
	$Account1->FirstName = "Lawrence";
	$Account1->LastName = "Turton";
	$Account1->AdditionalServices = "holiday package";

	$Account1->Deposit(1000);
	$Account1->Lock();
	$Account1->WithDraw(200);
	$Account1->Unlock();
	array_push( $Account1->Audit , array( "WITHDRAW ACCEPTED", 200, 800, "2016-01-30T11:41:16+01:00" ) );
	$Account1->WithDraw(159);

	// Savings Account

	$Account2 = new Savings;

	$Account2->APR = 12.0;
	$Account2->SortCode = "20-50-20";
	$Account2->FirstName = "Justin";
	$Account2->LastName = "Dike";
	$Account2->Package = "cartoon insurance";

	$Account2->Deposit(500);
	$Account2->Lock();
	$Account2->WithDraw(200);
	$Account2->Unlock();
	$Account2->WithDraw(159);
	
	//$Account2->AddedBonus();
	$Account2->OrderNewBook();
	$Account2->OrderNewDepositBook();

	// Debit Account

	$Account3 = new Debit;

	$Account3->APR = 0;
	$Account3->SortCode = "20-50-20";
	$Account3->FirstName = "Jason";
	$Account3->LastName = "Bourne";
	$Account3->Package = "spy insurance";

	$Account3->Deposit(15000);
	$Account3->Lock();
	$Account3->WithDraw(1200);
	$Account3->Unlock();
	$Account3->WithDraw(150);
	
	$Account3->AddedBonus();
	$Account3->ChangePin( 1234 );
	$Account3->Validate();

	echo json_encode($Account3, JSON_PRETTY_PRINT);

?>