<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'to' => 'required|array',
            'to.*' => 'email',
            'from' => 'required|email',
            'subject' => 'required|string',
            'template_type' => 'required|string|in:resetpassword,welcome,custom',
            'data' => 'required|array',
        ]);

        // For custom template type, require either html_content or template_id
        if ($request->input('template_type') === 'custom') {
            if (!$request->has('html_content') && !$request->has('template_id')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'For custom template type, either html_content or template_id is required'
                ], 400);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $to = $request->input('to');
            $from = $request->input('from');
            $subject = $request->input('subject');
            $templateType = $request->input('template_type');
            $data = $request->input('data', []);
            
            // Get HTML content based on template type
            $htmlContent = $this->getHtmlContent($request, $templateType, $data);
            
            // Send email
            Mail::html($htmlContent, function ($message) use ($to, $from, $subject) {
                $message->to($to)
                       ->from($from)
                       ->subject($subject);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Email sent successfully',
                'recipients' => count($to)
            ]);

        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getHtmlContent(Request $request, string $templateType, array $data)
    {
        // Handle different template types
        switch ($templateType) {
            case 'resetpassword':
                $htmlContent = $this->loadTemplateFromFile('resetpassword');
                break;
            case 'welcome':
                $htmlContent = $this->loadTemplateFromFile('welcome');
                break;
            case 'custom':
                // Priority 1: Use html_content if provided
                if ($request->has('html_content')) {
                    $htmlContent = $request->input('html_content');
                } 
                // Priority 2: Load template from file if template_id provided
                else if ($request->has('template_id')) {
                    $templateId = $request->input('template_id');
                    $htmlContent = $this->loadTemplateFromFile($templateId);
                }
                break;
            default:
                throw new \Exception("Unsupported template type: {$templateType}");
        }

        // Replace all placeholders dynamically
        return $this->replacePlaceholders($htmlContent, $data);
    }

    private function loadTemplateFromFile($templateId)
    {
        // Try to load from resources/email-templates directory
        $templatePath = resource_path("email-templates/{$templateId}.html");
        
        if (file_exists($templatePath)) {
            return file_get_contents($templatePath);
        }
        
        // If file doesn't exist, return a simple template
        throw new \Exception("Template '{$templateId}' not found at {$templatePath}");
    }

    private function replacePlaceholders($htmlContent, array $data)
    {
        // Add some default dynamic values
        $defaultData = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'date' => now()->format('Y-m-d'),
            'time' => now()->format('H:i:s'),
            'year' => now()->format('Y')
        ];

        // Merge user data with defaults (user data takes priority)
        $allData = array_merge($defaultData, $data);

        // Replace all {{key}} placeholders with values
        foreach ($allData as $key => $value) {
            // Handle nested arrays/objects by converting to string
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            
            $htmlContent = str_replace('{{' . $key . '}}', $value, $htmlContent);
        }

        return $htmlContent;
    }
}