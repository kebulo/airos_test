<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Config;
use App\Models\Quotation;
use DateTime;
use Exception;

class QuotationController extends Controller
{

    public function index()
    {
        try {
            $quotations = Quotation::all();

            return response()->json([
                'status' => true,
                'quotations' => $quotations
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error getting quotations data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get one quotation from param.
     *
     * @param int $id -> Quotation ID to search
     * @return \Illuminate\Http\JsonResponse  200 and quotation data if success, 500 error if fails
     */
    public function show($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);

            return response()->json([
                'status' => true,
                'quotation' => $quotation
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error getting the quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set the data and calculate the quotation total amount
     * @param \App\http\Requests\StoreQuotationRequest $request -> Params to calculate the quotation I.e {"ages": "28,35", "currency_id": "EUR"...}
     * @return \Illuminate\Http\JsonResponse -> Quotation value and details 
     */
    public function calculateQuotation(StoreQuotationRequest $request)
    {
        try {
            $ages_list = explode(',', $request->ages);
            $currency_id = $request->currency_id;
            $start_date = new DateTime($request->start_date);
            $end_date = new DateTime($request->end_date);
            $total_quotation = 0;

            $config = $this->getConfigData();

            foreach ($ages_list as $age) {
                $total_quotation += $this->calculateQuotationPrice(
                    $age,
                    $config->formatAgeLoadData(),
                    $config->fixed_rate,
                    $start_date,
                    $end_date
                );
            }

            $quotation = [
                'ages' => $request->ages,
                'currency_id' => $currency_id,
                'price' => $total_quotation,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            $quotation_save_response = $this->store(new StoreQuotationRequest($quotation));

            return $quotation_save_response;
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error calculating quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new quotation.
     *
     * @param  \App\Http\Requests\StoreQuotationRequest $request -> Data send as parameter to save the quotation
     * @return \Illuminate\Http\JsonResponse 200 and quotation data if success, 500 error if fails
     */
    public function store(StoreQuotationRequest $request)
    {
        try {
            $quotation = new Quotation();

            $quotation->ages = $request->input('ages');
            $quotation->currency_id = $request->input('currency_id');
            $quotation->price = $request->input('price');
            $quotation->start_date = $request->input('start_date');
            $quotation->end_date = $request->input('end_date');

            $quotation->save();

            $formattedQuotationData = [
                'total' => $quotation->price,
                'currency_id' => $quotation->currency_id,
                'quotation_id' => $quotation->id,
            ];

            return response()->json([
                'status' => true,
                'message' => "Quotation saved successfully",
                'quotation' => $formattedQuotationData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error saving quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update quotation data.
     *
     * @param  \App\Http\Requests\StoreQuotationRequest $request
     * @param  \App\Models\Quotation  $quotation -> Quotation data to be updated
     * @return \Illuminate\Http\JsonResponse 200 and quotation data if success, 500 error if fails
     */
    public function update(StoreQuotationRequest $request, $id)
    {
        try {
            $quotation = Quotation::findOrFail($id);

            $quotation->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'The quotation was updated successfully!',
                'quotation' => $quotation
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'The Quotation to update was not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a quetiontation based on the ID
     *
     * @param  \App\Models\Quotation $quotation
     * @return \Illuminate\Http\JsonResponse 200 if success, 500 error if fails
     */
    public function destroy(Quotation $quotation)
    {
        try {
            $quotation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Quotation Deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get configuration data from Model
     * @return object \App\Models\Config -> config data Ie. {age_range: "18-30, 31-40"...}
     */
    private function getConfigData()
    {
        $config = Config::firstOrFail();

        return $config;
    }

    /**
     * Calculate quotation price per individual age
     * @param int $age -> Client age Ie. 20
     * @param array $configData -> Associative array with the age_range and the loads I.e ['18-30' => 0.6... ]
     * @param int $fix_daily_term -> Fix daily term configured I.e 3
     * @param object $start_date -> Start date of the trip 
     * @param object $end_date -> End date of the trip 
     * @return float $quotation  -> Quotation calculated based on parameters
     */
    private function calculateQuotationPrice($age, $configData, $fix_daily_term, $start_date, $end_date)
    {

        $trip_amount_days = $start_date->diff($end_date)->format("%a") + 1;
        $load = 0;

        foreach ($configData as $key => $value) {
            $ages = explode('-', $key);

            if ($age >= (int) $ages[0] && $age <= (int) $ages[1]) {
                $load = $value;
            }
        }

        $quotation = ($load * $fix_daily_term * $trip_amount_days);

        return (float) number_format($quotation, 2, '.', '');
    }
}