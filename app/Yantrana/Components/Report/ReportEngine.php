<?php
/*
* ReportEngine.php - Main component file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report;

use App\Yantrana\Components\Report\Repositories\ReportRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\ShoppingCart\OrderEngine;
use App\Yantrana\Components\Report\Blueprints\ReportEngineBlueprint;
use Config;
use PDF;
use Excel;
use App;

class ReportEngine implements ReportEngineBlueprint
{
    /**
     * @var ReportRepository - Report Repository
     */
    protected $reportRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var OrderEngine - Order Engine
     */
    protected $orderEngine;

    /**
     * Constructor.
     *
     * @param ReportRepository $reportRepository - Report Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
                    ReportRepository $reportRepository,
                    ShippingRepository $shippingRepository,
                    TaxRepository $taxRepository,
                    SupportRepository $supportRepository,
                    OrderEngine $orderEngine
                ) {
        $this->reportRepository = $reportRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->supportRepository = $supportRepository;
        $this->orderEngine = $orderEngine;
    }

    /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList($startDate, $endDate, $status, $order)
    {
        return $this->reportRepository
                    ->fetchDataTableSource($startDate, $endDate, $status, $order);
    }

    /**
     * Get total by currency code.
     *
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param $order
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getTotalAmountByCurrency($startDate, $endDate)
    {
        $totalOrderAmounts = $this->reportRepository
                              ->fetchTotalAmountByCurrency($startDate, $endDate);

        $orderAmountByType = [];

        // Calculate order total amount by currency
        $data = $totalOrderAmounts->each(function ($item, $key) use (&$orderAmountByType) {

            // Get currency code
            $currencyCode = $item->currency_code;

            // Get total credit amount of order group by currency code
            $creditAmount = $item->where([
                    'currency_code' => $currencyCode,
                    'type' => 1,
                ])->sum('gross_amount');

            // Get total debit amount of order group by currency code
            $debitAmount = $item->where([
                    'currency_code' => $currencyCode,
                    'type' => 2,
                ])->sum('gross_amount');

            // Calculate total amount
            $totalAmount = $creditAmount - $debitAmount;

            $orderAmountByType[$currencyCode] = [
                    'currencyCode' => $currencyCode,
                    'credit' => $creditAmount,
                    'formattedCredit' => orderPriceFormat($creditAmount, $currencyCode),
                    'debit' => $debitAmount,
                    'formattedDebit' => orderPriceFormat($debitAmount, $currencyCode),
                    'total' => $totalAmount,
                    'formattedTotal' => orderPriceFormat($totalAmount, $currencyCode),
            ];
        });

        return [
            'orderAmountByType' => $orderAmountByType,
        ];
    }

    /**
     * prepare order detail dialog data.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderDetailsDialogData($orderID)
    {
        // Get order detail with products, address and coupon
        $orderDetails = $this->reportRepository
                             ->fetchOrderDetails($orderID);

         //check order exist
        if (empty($orderDetails)) {
            return __engineReaction(18);
        }

        // get tax detail
        $taxCollection = $this->reportRepository
                               ->fetchOrderTax($orderDetails['_id'])->toArray();

        // Get order currency code
        $currencyCode = $orderDetails['currency_code'];

        // get country code from user address
        foreach ($orderDetails['address'] as $address) {
            $countryCode = $address->country;
        }

        // get shipping data
        $shippingData = [];

        $shippingCollection = $this->shippingRepository
                                   ->fetchByConutry($countryCode);

        // Check if shipping exist and make shipping data array for shipping data
        if (!empty($shippingCollection)) {
            $shippingData = [
                'shippingNotes' => $shippingCollection['notes'],
                'shippingType' => $shippingCollection['type'],
            ];
        }

           // check shipping amount exist
        $shipping = 0;
        if (!empty($orderDetails->shipping_amount)) {
            $shipping = $orderDetails->shipping_amount;
        }

        // get tax data
        $taxData = $this->taxRepository
                        ->fetchByConutry($countryCode);

        // calculation of order products and addon price
        $orderProducts = $orderDetails->orderProduct;

        $getSubTotal = [];

        foreach ($orderProducts as $optionKey => $orderProduct) {
            $addonPrice = [];
                // check product option
                if (!empty($orderProduct['productOption'])) {
                    foreach ($orderProduct['productOption'] as $key => $productOption) {
                        $productOption['addonPrice'] = orderPriceFormat($productOption['addon_price'], $currencyCode);

                        $addonPrice[] = $productOption['addon_price'];
                    }
                }
                // get add price total
                $totalAddonPrice = array_sum($addonPrice);

                // price and addon price total
                $addOptionPriceInAddon = $orderProduct['price'] + $totalAddonPrice;

                //add price formate
                $orderProducts[$optionKey]['priceFormat'] = orderPriceFormat($orderProduct['price'], $currencyCode);

                //create new price with addon price and price
                $orderProducts[$optionKey]['new_price'] = orderPriceFormat($addOptionPriceInAddon, $currencyCode);

                // add quantity and price
                $addQuntity = $addOptionPriceInAddon * $orderProduct['quantity'];

                // add sub total price
                $orderProducts[$optionKey]['sub_total'] = orderPriceFormat($addQuntity, $currencyCode);

            $getSubTotal[] = $addQuntity;
        }

            // calculate tax amount
            $taxData = [];
        $taxCharges = [];
        $totalTax = 0;

        if (!empty($taxCollection)) {
            foreach ($taxCollection as $key => $taxDiscount) {
                //get tax detail
                    $taxes = $this->taxRepository
                                  ->fetchByTaxId($taxDiscount['tax__id'])->toArray();

                if (!empty($taxes)) {
                    foreach ($taxes as $tax) {
                        // push individual tax data	into array
                                $taxData [] = [
                                    'label' => $tax['label'],
                                    'notes' => $tax['notes'],
                                    'type' => $tax['type'],
                                    'discount' => $taxDiscount['amount'],
                                    'formatedTax' => orderPriceFormat($taxDiscount['amount'], $currencyCode),
                                ];
                    }
                } else {
                    // if tax not exist in db
                            $taxData [] = [
                                    'label' => '',
                                    'notes' => '',
                                    'discount' => $taxDiscount['amount'],
                                    'formatedTax' => orderPriceFormat($taxDiscount['amount'], $currencyCode),
                                ];
                }

                $taxCharges[$key] = $taxDiscount['amount'];
            }
                // sum of tax
                $totalTax = array_sum($taxCharges);
        }

            // check coupon detail exist
            if (!empty($orderDetails->coupon)) {
                $couponData = [
                    'code' => $orderDetails->coupon->code,
                    'title' => $orderDetails->coupon->title,
                    'description' => $orderDetails->coupon->description,
                ];
            }

            // calculation of discount (coupon)
            $discountAmount = $orderDetails->discount_amount;
        $basePrice = 0;
        $baseTotal = array_sum($getSubTotal);
        $total = 0;

            // if Base total exist
            if (!empty($baseTotal)) {

                // if discount amount exist then subtract from base total
                if (!empty($discountAmount)) {
                    $basePrice = $baseTotal - $discountAmount;
                    $total = $basePrice + $shipping + $totalTax;
                } else {
                    $basePrice = $baseTotal;
                    $total = $baseTotal + $shipping + $totalTax;
                }
            }

        $shippingAddress = [];
        $billingAddress = [];
        $addressType = config('__tech.address_type');
        $addressSameAs = false;

            //order shipping address
            foreach ($orderDetails->address as $address) {

                // Get country name
                $countryName = $this->supportRepository
                                       ->fetchCountry($address['countries__id']);

                $shippingAddress = [
                    'addressID' => $address['id'],
                    'type' => $addressType[$address['type']],
                    'address_line_1' => $address['address_line_1'],
                    'address_line_2' => $address['address_line_2'],
                    'city' => $address['city'],
                    'state' => $address['state'],
                    'country' => $countryName['name'],
                    'pincode' => $address['pin_code'],
                ];
            }

            //order billing address
            foreach ($orderDetails->address1 as $address1) {

                // Get country name
                $countryName = $this->supportRepository
                                       ->fetchCountry($address1['countries__id']);

                $billingAddress = [
                    'address1ID' => $address1['id'],
                    'type' => $addressType[$address1['type']],
                    'address_line_1' => $address1['address_line_1'],
                    'address_line_2' => $address1['address_line_2'],
                    'city' => $address1['city'],
                    'state' => $address1['state'],
                    'country' => $countryName['name'],
                    'pincode' => $address1['pin_code'],
                ];
            }

            //check address same as or not
            if ($shippingAddress['addressID'] == $billingAddress['address1ID']) {
                $addressSameAs = true;
            }

            // get order status
            $orderStatus = config('__tech.orders.status_codes');
        $orderPaymentMethod = config('__tech.orders.payment_methods');
        $orderPaymentStatus = config('__tech.orders.payment_status');

        $allOrderRelatedData = [
                'orderUID' => $orderDetails->order_uid,
                'name' => $orderDetails->name,
                'orderStatus' => $orderStatus[$orderDetails->status],
                'orderOn' => formatStoreDateTime($orderDetails->created_at),
                'orderBy' => $orderDetails->user->email,
                'paymentMethod' => $orderPaymentMethod[$orderDetails->payment_method],
                'paymentStatus' => $orderPaymentStatus[$orderDetails->payment_status],
                'currencyCode' => $currencyCode,
                'cartTotal' => orderPriceFormat($baseTotal, $currencyCode),
                'total' => orderPriceFormat($total, $currencyCode),
                'shipping' => (!empty($shipping))
                                    ? orderPriceFormat($shipping, $currencyCode)
                                    : '',
                'shippingData' => (!empty($shippingData))
                                    ? $shippingData
                                    : '',
                'couponData' => (!empty($couponData))
                                    ? $couponData
                                    : '',
                'discount' => (!empty($discountAmount))
                                    ? orderPriceFormat($discountAmount, $currencyCode)
                                    : '',
                'totalTax' => (!empty($totalTax))
                                    ? orderPriceFormat($totalTax, $currencyCode)
                                    : '',
                'taxData' => (!empty($taxData))
                                    ? $taxData
                                    : '',
            ];

            // get logo of store
               $logoURL = getStoreSettings('logo_image');

        return __engineReaction(1, [
                'orderDetails' => $allOrderRelatedData,
                'productOrder' => $orderDetails->orderProduct,
                'orderAddress' => $shippingAddress,
                'orderAddress1' => $billingAddress,
                'sameAddress' => $addressSameAs,
                'logoURL' => $logoURL,
                ]);
    }

    /**
     * process pdf download.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processPdfDownload($orderID)
    {
        // get order detail by order ID
        $orderDetails = $this->orderEngine
                             ->prepareForMyOrderDetails((int) $orderID);

        if ($orderDetails['reaction_code'] == 18) {
            App:abort(404);
        }

        // array data for creation of string for pdf
        $arrayData = [
            ':currentDate' => formatStoreDateTime(currentDateTime()),
        ];

        // generated on string 
        $orderDetails['currentDateTime'] = __('Generated on :currentDate', $arrayData);

        // download pdf
        $reportPdf = PDF::loadView('report.manage.pdf-report', ['orderDetails' => $orderDetails]);

        return $reportPdf->download(str_slug($orderDetails['data']['order']['orderUID']).'.pdf');
    }

    /**
     * process Excel Download.
     *
     * @param date $startDate
     * @param date $endData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processExcelDownload($startDate, $endDate, $status, $orderCode)
    {
        $orderCollection = $this->reportRepository
                                 ->fetchOrderCollection($startDate, $endDate, $status, $orderCode);

        // Get order amount by currency
        $orderAmountByCurrency = $this->getTotalAmountByCurrency($startDate, $endDate);

        // Check if order collection is empty
        if (__isEmpty($orderCollection)) {
            App:abort(404);
        }

        // get order array
        $orderStatus = config('__tech.orders.status_codes');
        $paymentMethod = config('__tech.orders.payment_methods');
        $orderType = config('__tech.orders.payment_type');

        $totalOrderAmountCollection = [];
        $totalOrderAmount = 0;
        $orderData = [];
        $taxCharges = [];
        $totalTax = 0;

        foreach ($orderCollection as $key => $order) {

            // get all total amount of order
            $totalOrderAmountCollection[$key] = $order['total_amount'];

            // get tax detail
            $taxCollection = $this->reportRepository
                                  ->fetchOrderTax($order['_id'])->toArray();

            // push tax amount into array         
            foreach ($taxCollection as $key => $tax) {
                $taxCharges[$key] = $tax['amount'];
            }

            //calculate total tax
            $totalTax = array_sum($taxCharges);

            $orderData [] = [
                'orderUID' => $order['order_uid'],
                'fullName' => $order['fname'].' '.$order['lname'],
                'placedOn' => formatStoreDateTime($order['created_at']),
                'status' => $orderStatus[$order['status']],
                'type' => $orderType[$order['type']],
                'paymentMethod' => $paymentMethod[$order['payment_method']],
                'currency' => $order['currency_code'],
                'total' => $order['total_amount'],
                'discountAmount' => (!__isEmpty($order['discount_amount']))
                                        ? $order['discount_amount']
                                        : '',
                'shippingAmount' => (!__isEmpty($order['shipping_amount']))
                                        ? $order['shipping_amount']
                                        : '',
                'totalAmount' => (!__isEmpty($totalTax))
                                        ? $totalTax
                                        : '',
            ];
        }

        // Create a data for total order amount received and refunded
        $currencyTotalAmountData = [];

        foreach ($orderAmountByCurrency['orderAmountByType'] as $orderAmt) {
            $currencyTotalAmountData [] = [
                'currencyCode' => $orderAmt['currencyCode'],
                'credit' => $orderAmt['credit'],
                'debit' => $orderAmt['debit'],
                'difference' => $orderAmt['total'],
            ];
        }

        // total of all order 
        $totalOrderAmount = array_sum($totalOrderAmountCollection);

        //set excel file name
        $ExcelFileName = 'Report-'.''.$startDate.'-'.$endDate;

        // set start date and end date title
        if ($orderCode == 1) {
            $startAndEndDate = 'From'.' '.$startDate.' to '.$endDate.' Placed on';
        } else {
            $startAndEndDate = 'From'.' '.$startDate.' to '.$endDate.' Updated on';
        }

        // set order title with date and time
        $currentDate = formatStoreDateTime(currentDateTime());
        $orderTitle = 'Orders as on '.''.$currentDate;

        return Excel::create($ExcelFileName, function ($excel) use ($orderData, $startAndEndDate,
            $totalOrderAmount, $orderTitle, $currencyTotalAmountData) {

            $excel->sheet('orders', function ($sheet) use ($orderData, $startAndEndDate, $totalOrderAmount, $orderTitle, $currencyTotalAmountData) {

                //merge cells
                $sheet->mergeCells('A1:K1');//merge for store name
                $sheet->mergeCells('A2:K2');//merge for title
                $sheet->mergeCells('A3:K3');//merge for start and end date

                //set font size
                $sheet->cells('A1:K1', function ($cells) {
                    $cells->setFontSize(14);//font size
                    $cells->setFontWeight('bold');//bold text
                });

                //set alignment
                $sheet->cells('A2:K3', function ($cells) {
                    $cells->setAlignment('center');//alignment center
                    $cells->setFontWeight('bold');//bold text
                    $cells->setFontSize(14);//font size
                });

                // count all order and set border for it
                $orderCount = count($orderData);
                $rowCount = 4 + $orderCount;
                $cellRange = 'A1:K'.$rowCount;

                // set border 
                $sheet->setBorder($cellRange, 'thin');

                // store name
                $sheet->row(1, [getStoreSettings('store_name')])->setHeight(1, 30);

                // current date and time
                $sheet->row(2, [$orderTitle]);

                // set start And EndDate for excel sheet
                $sheet->row(3, [$startAndEndDate]);

                // Heading column for excel sheet
                $sheet->row(4, array(
                    'OrderUID',
                    'Full Name',
                    'Order Placed on',
                    'Status',
                    'Type',
                    'Payment Method',
                    'Currency',
                    'Total',
                    'Discount Amount',
                    'Shipping Amount',
                    'Total Tax',
                ));

                $sheet->fromArray($orderData, null, 'A5', true, false);

                // Calculate row from last data of array
                $rowID = $rowCount + 2;
                $selectedCell = 'A'.$rowID.':'.'D'.$rowID;
                $sheet->mergeCells($selectedCell);

                //set alignment of total order Amount
                $sheet->cells($selectedCell, function ($cells) {
                    $cells->setFontSize(13); //font size
                    $cells->setFontWeight('bold'); //bold text
                });

                // Total orders Amount data
                $sheet->row($rowID, ['Total Order Payments']);

                $sheet->row($rowID + 1, [
                    'Currency',
                    'Credit Amount',
                    'Debit Amount',
                    'Difference Amount',
                ]);

                $sheet->rows($currencyTotalAmountData, null, 'A'.$rowID + 2, true, false);

                // count all order payment amount and set border for it
                $orderAmtCount = count($currencyTotalAmountData);
                $amountRowCount = $rowID + $orderAmtCount + 1;
                $cellRange = 'A'.$rowID.':D'.$amountRowCount;

                // set border 
                $sheet->setBorder($cellRange, 'thin');

            });

        })->export('xls');
    }
}
