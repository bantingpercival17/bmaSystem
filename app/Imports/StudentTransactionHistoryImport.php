<?php

namespace App\Imports;

use App\Models\EnrollmentAssessment;
use App\Models\PaymentAssessment;
use App\Models\PaymentTransaction;
use App\Models\StudentAccount;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentTransactionHistoryImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $_log_path = "/accounting/payment-transaction-import/student-transaction-"  . date('dmy') . ".log";
        echo "<table>";
        echo '<thead><tr><th>STUDENT NUMBER</><th>STUDENT NAME</th><th>ENROLLMENT ASSESSMENT</th><th>PAYMENT ASSESSMENT </th><th>PAYMENT TRANSACTION</th></tr></thead>';
        echo "<tbody>";
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                echo "<tr>";
                $_student_number = $_data[0]; // Get the Student Number
                $_student_account = StudentAccount::where('student_number', $_student_number)->first(); // Find the Student using the Student Number
                if ($_student_account) {
                    echo '<td style="width:13%"> ' .  $_student_account->student_number . "</td>";
                    echo '<td  style="width:15%">' . strtoupper($_student_account->student->last_name . ', ' . $_student_account->student->first_name) .  "</td>";;
                    if ($_data[2] != 'null') {
                        $_enrollment_assessment_excel = json_decode($_data[2]);
                        $enrollment_assessment = EnrollmentAssessment::where('student_id', $_student_account->student_id)->where('academic_id', $_enrollment_assessment_excel->academic_id)->first();
                        if ($enrollment_assessment) {
                            echo '<td  style="width:15%">';
                            echo "EXCEL: HAVED DATA <br>";
                            echo "DATABASE: SAVED";
                            echo "</td>";
                            if ($_data[3] != 'null') {
                                $payment_assessment_excel = json_decode($_data[3]);
                                $payment_assessment = $enrollment_assessment->payment_assessments;
                                if ($payment_assessment) {
                                    echo "<td>";
                                    echo "DATABASE: SAVED DATA";
                                    $payment_assessment->payment_mode = $payment_assessment_excel->mode_payment;
                                    $payment_assessment->voucher_amount = $payment_assessment_excel->voucher_discount;
                                    $payment_assessment->total_payment = $payment_assessment_excel->total_enrollment_payment;
                                    $payment_assessment->save();
                                    echo "<br>UPDATE DATA";
                                    echo "</td>";
                                    // Payment Transaction

                                    if ($_data[4] != "null") {
                                        $payment_transaction_excel = json_decode($_data[4]);
                                        if (count($payment_transaction_excel) > 0) {
                                            echo "<td>";
                                            foreach ($payment_transaction_excel as $key => $payment) {
                                                if ($payment->or_number !== null) {
                                                    $_payment_transaction =  PaymentTransaction::where('or_number', $payment->or_number)->first();
                                                    if ($_payment_transaction) {
                                                        #If Payment is Saved Update the Payment Assessment Id
                                                        $user = $payment->processed_admin_id == 2 ? 4 : 3;
                                                        $_payment_transaction->staff_id = $user;
                                                        $_payment_transaction->assessment_id = $payment_assessment->id;
                                                        $_payment_transaction->transaction_date = $payment->transaction_date;
                                                        $_payment_transaction->created_at = $payment->created_at;
                                                        $_payment_transaction->save();
                                                        echo "<br>" . $payment->or_number . " : TRANSACTION UPDATE";
                                                        # code...
                                                    } else {
                                                        $user = $payment->processed_admin_id == 2 ? 4 : 5;
                                                        $transaction = array(
                                                            'assessment_id' => $payment_assessment->id,
                                                            'or_number' => $payment->or_number,
                                                            'payment_amount' => $payment->payment_amount,
                                                            'payment_method' => $payment->payment_method,
                                                            'remarks' => $payment->remarks,
                                                            'payment_transaction' => 'TUITION FEE',
                                                            'transaction_date' => $payment->transaction_date,
                                                            'staff_id' => $user,
                                                            'is_removed' => 0,
                                                            'created_at' => $payment->created_at
                                                        );
                                                        PaymentTransaction::create($transaction);
                                                        echo "<br>" . $payment->or_number . " : TRANSACTION SAVED";
                                                    }
                                                }
                                            }
                                            echo "</td>";
                                        }
                                    } else {
                                        echo "<td>";
                                        echo "EXCEL : NO DATA";
                                        echo "</td>";
                                    }
                                } else {
                                    echo "<td>";
                                    echo "DATABASE: NO DATA";
                                    $_assessment_detials = array(
                                        "enrollment_id" => $enrollment_assessment->id,
                                        "payment_mode" => $payment_assessment_excel->mode_payment,
                                        "course_semestral_fee_id" => null,
                                        "voucher_amount" => $payment_assessment_excel->voucher_discount,
                                        "total_payment" => $payment_assessment_excel->total_enrollment_payment,
                                        "staff_id" => 4,
                                        "is_removed" => 0
                                    );
                                    echo "<br> SAVING.....";
                                    $payment_assessment = PaymentAssessment::create($_assessment_detials);
                                    echo "<br> SAVED";
                                    echo "</td>";
                                    if ($_data[4] != "null") {
                                        $payment_transaction_excel = json_decode($_data[4]);
                                        if (count($payment_transaction_excel) > 0) {
                                            echo "<td>";
                                            foreach ($payment_transaction_excel as $key => $payment) {
                                                if ($payment->or_number !== null) {
                                                    $_payment_transaction =  PaymentTransaction::where('or_number', $payment->or_number)->first();
                                                    if ($_payment_transaction) {
                                                        #If Payment is Saved Update the Payment Assessment Id
                                                        $user = $payment->processed_admin_id == 2 ? 4 : 3;
                                                        $_payment_transaction->staff_id = $user;
                                                        $_payment_transaction->assessment_id = $payment_assessment->id;
                                                        $_payment_transaction->transaction_date = $payment->transaction_date;
                                                        $_payment_transaction->created_at = $payment->created_at;
                                                        $_payment_transaction->save();
                                                        echo "<br>" . $payment->or_number . " : TRANSACTION UPDATE";
                                                        # code...
                                                    } else {
                                                        $user = $payment->processed_admin_id == 2 ? 4 : 5;
                                                        $transaction = array(
                                                            'assessment_id' => $payment_assessment->id,
                                                            'or_number' => $payment->or_number,
                                                            'payment_amount' => $payment->payment_amount,
                                                            'payment_method' => $payment->payment_method,
                                                            'remarks' => $payment->remarks,
                                                            'payment_transaction' => 'TUITION FEE',
                                                            'transaction_date' => $payment->transaction_date,
                                                            'staff_id' => $user,
                                                            'is_removed' => 0,
                                                            'created_at' => $payment->created_at
                                                        );
                                                        PaymentTransaction::create($transaction);
                                                        echo "<br>" . $payment->or_number . " : TRANSACTION SAVED";
                                                    }
                                                }
                                            }
                                            echo "</td>";
                                        }
                                    } else {
                                        echo "<td>";
                                        echo "EXCEL : NO DATA";
                                        echo "</td>";
                                    }
                                }
                            } else {
                                echo "<td>";
                                echo "EXCEL : NO DATA";
                                echo "</td>";
                            }
                        } else {
                            echo "<td>";
                            echo "EXCEL : NO DATA";
                            echo "</td>";
                        }
                    } else {
                        echo "<td colspan='3'> No Payment Details </td>";
                    }
                } else {
                    echo "<td>" . $_student_number . "</td>";
                    echo "<td colspan='4'> No Student Details </td>";
                }
                echo "</tr>";
                echo "<tr><td colspan='5'></td><tr>";
            }
        }
        echo "</tbody></table>";
    }
}
