<?php

	require("SubClasses.php");

	$Account1 = new ISA( 35, "holiday package", 5.0, "20-20-20", "Lawrence", "Turton", 1000, true );

	$Account1->Deposit(1000);
	$Account1->Lock();
	$Account1->WithDraw(200);
	$Account1->Unlock();
	array_push( $Account1->Audit , array( "WITHDRAW ACCEPTED", 200, 800, "2016-01-30T11:41:16+01:00" ) );
	$Account1->WithDraw(159);

	// Savings Account

	$Account2 = new Savings( 50, "cartoon insurance", 12.0, "20-50-20", "Justin", "Dike" );

	$Account2->Deposit(500);
	$Account2->Lock();
	$Account2->WithDraw(200);
	$Account2->Unlock();
	$Account2->WithDraw(159);
	
	//$Account2->AddedBonus();
	$Account2->OrderNewBook();
	$Account2->OrderNewDepositBook();

	// Debit Account

	$Account3 = new Debit( 30, "spy insurance", 1234, 0, "20-50-20", "Jason", "Bourne" );

	$Account3->Deposit(15000);
	$Account3->Lock();
	$Account3->WithDraw(1200);
	$Account3->Unlock();
	$Account3->WithDraw(150);
	
	//$Account3->AddedBonus();

	// Array

	$AccountList = array( $Account1, $Account2, $Account3 );

	/*foreach( $AccountList as $Account ){

		$print = $Account->FirstName;

		if( $Account instanceof AccountPlus ) {

			$print .= " AddedBonus()";

		}

		if( $Account instanceof Savers ) {

			$print .= " OrderNewBook() OrderNewDepositBook()";

		}


		echo $print ."<br/>";

	}*/

	echo $Account1::stat();

?>