<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function success(string $message = null, int $code = 200, $data = null)
	{
        // set array response
        $arrResponse = [
            'status'    => 1,
            'message'   => $message
        ];

        // if data not null
        if ($data) {
            // append data to array response
            $arrResponse['data'] = $data;
        }

		return response()->json($arrResponse, $code);
	}

	public function error(string $message = null, int $code)
	{
		return response()->json(
            [
                'status'  => 0,
                'message' => $message
		    ],
            $code
        );
	}
}
