<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendEmail(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|array',
            'to.*' => 'email',
            'from' => 'required|email',
            'subject' => 'required|string',
            'template_id' => 'nullable|string',
            'html_content' => 'nullable|string',
            'data' => 'nullable|array'
        ]);

        try {
            $result = $this->emailService->sendEmail([
                'to' => $request->to,
                'from' => $request->from,
                'subject' => $request->subject,
                'template_id' => $request->template_id,
                'html_content' => $request->html_content,
                'data' => $request->data ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
