<?php

	require("BankAccount.php");

	class ISA extends BankAccount{

		private $TimePeriod;

		public $AdditionalServices;

		// Constructor

		public function __construct( $time, $service, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ){

			$this->TimePeriod = $time;

			$this->AdditionalServices = $service;

			parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );

		}

		//Methods

		public function WithDraw($amount){

			$transDate = new DateTime();

			$lastTransaction = null;

			$length = count($this->Audit);

			for( $i = $length; $i > 0; $i-- ){

				$element = $this->Audit[$i - 1];

				if( $element[0] === "WITHDRAW ACCEPTED" ){

					$days = new DateTime( $element[3] );

					$lastTransaction = $days->diff($transDate)->format("%a");

					break;

				}

			}

			if( $lastTransaction === null && $this->Locked === false || $this->Locked === false && $lastTransaction > $this->TimePeriod ){

				$this->Balance -= $amount;

				array_push( $this->Audit, array( "WITHDRAW ACCEPTED", $amount, $this->Balance, $transDate->format('c') ) );
				

			} else {

				if( $this->Locked === false ) {

					$this->Balance -= $amount;

					array_push( $this->Audit, array( "WITHDRAW ACCEPTED WITH PENALTY", $amount, $this->Balance, $transDate->format('c') ) );

					$this->Penalty();

				} else {

					array_push( $this->Audit, array( "WITHDRAW DENIED", $amount, $this->Balance, $transDate->format('c') ) );

				}

			}

			//echo parent::stat();

		}

		private function Penalty(){

			$transDate = new DateTime();

			$this->Balance -= 10;

			array_push( $this->Audit, array( "WITHDRAW PENALTY", 10, $this->Balance, $transDate->format('c') ) );

		}

	}

	trait SavingsPlus{

		private $MonthlyFee = 20;

		public $Package = "holiday insurance";

		//Method...

		public function AddedBonus(){

			echo "Hello ". $this->FirstName ." ". $this->LastName ." for &pound;". $this->MonthlyFee ." a month you get ". $this->Package;

		}

	}

	interface AccountPlus{

		public function AddedBonus();

	}

	interface Savers{

		public function OrderNewBook();
		public function OrderNewDepositBook();

	}

	class Savings extends BankAccount implements AccountPlus, Savers{

		use SavingsPlus;

		public $PocketBook = array();

		public $DepositBook = array();

		// Constructor

		public function __construct( $fee, $package, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ){

			$this->MonthlyFee = $fee;

			$this->Package = $package;

			parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );

		}

		//Methods

		public function OrderNewBook(){

			$orderTime = new DateTime();

			array_push( $this->PocketBook, "Ordered new pocket book on: ". $orderTime->format('c') );

		}

		public function OrderNewDepositBook(){

			$orderTime = new DateTime();

			array_push( $this->DepositBook, "Ordered new deposit book on: ". $orderTime->format('c') );

		}

	}

	class Debit extends BankAccount implements AccountPlus{

		use SavingsPlus;

		private $CardNumber;

		private $SecurityCode;

		private $PinNumber;

		// Constructor

		public function __construct( $fee, $package, $pin, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ){

			$this->MonthlyFee = $fee;

			$this->Package = $package;

			$this->PinNumber = $pin;

			$this->Validate();

			parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );

		}

		//Methods

		private function Validate(){

			$valDate = new DateTime();

			$this->CardNumber = rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999);

			$this->SecurityCode = rand(100, 999);

			array_push( $this->Audit, array( "VALIDATED CARD", $valDate->format('c'), $this->CardNumber, $this->SecurityCode, $this->PinNumber ) );
			
		}

		public function ChangePin( $newPin ){

			$pinChange = new DateTime();

			$this->PinNumber = $newPin;

			array_push( $this->Audit, array( "PIN CHANGED", $pinChange->format('c'), $this->PinNumber ) );

		}

	}

?>