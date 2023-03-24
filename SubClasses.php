<?php

	require("BankAccount.php");

	class ISA extends BankAccount{

		public $TimePeroid = 28;

		public $AdditionalServices;

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

			if( $lastTransaction === null && $this->Locked === false || $this->Locked === false && $lastTransaction > $this->TimePeroid ){

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

		}

		private function Penalty(){

			$transDate = new DateTime();

			$this->Balance -= 10;

			array_push( $this->Audit, array( "WITHDRAW PENALTY", 10, $this->Balance, $transDate->format('c') ) );

		}

	}

	class Savings extends BankAccount{

		public $PocketBook = array();

		public $DepositBook = array();

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

	class Debit extends BankAccount{

		private $CardNumber;

		private $SecuirtyCode;

		private $PinNumber;

		//Methods

		public function Validate(){

			$valDate = new DateTime();

			$this->CardNumber = rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999);

			$this->SecuirtyCode = rand(100, 999);

			array_push( $this->Audit, array( "VALIDATED CARD", $valDate->format('c'), $this->CardNumber, $this->SecuirtyCode, $this->PinNumber ) );
			
		}

		public function ChangePin( $newPin ){

			$pinChange = new DateTime();

			$this->PinNumber = $newPin;

			array_push( $this->Audit, array( "PIN CHANGED", $pinChange->format('c'), $this->PinNumber ) );

		}

	}

?>