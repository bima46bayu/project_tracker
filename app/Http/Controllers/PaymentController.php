<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function sync(Request $request, Project $project)
    {
        $validated = $request->validate([
            'payment_term' => 'nullable|string',
            'subkons' => 'nullable|array',
            'subkons.*.subkon_id' => 'required|exists:subkons,id',
            'subkons.*.payment_term' => 'nullable|string',
            'payments' => 'nullable|array',
        ]);

        // 1. Update Project Main Payment Term
        $project->update(['payment_term' => $validated['payment_term'] ?? null]);

        // 2. Update Subkon Payment Terms (Pivot)
        if (isset($validated['subkons'])) {
            foreach ($validated['subkons'] as $sub) {
                $project->subkons()->updateExistingPivot($sub['subkon_id'], [
                    'payment_term' => $sub['payment_term'] ?? null
                ]);
            }
        }

        // 3. Sync Payments
        $payments = $validated['payments'] ?? [];
        $existingIds = collect($payments)->pluck('id')->filter()->toArray();
        
        // Delete payments not included in the request
        Payment::where('project_id', $project->id)->whereNotIn('id', $existingIds)->delete();

        foreach ($payments as $pData) {
            $data = [
                'subkon_id' => $pData['subkon_id'] ?? null,
                'type' => $pData['type'],
                'invoice' => $pData['invoice'] ?? null,
                'keterangan' => $pData['keterangan'] ?? null,
                'nilai' => $pData['nilai'] ?? 0,
                'tanggal' => $pData['tanggal'] ?? null,
                'tanggal_payment' => $pData['tanggal_payment'] ?? null,
                'nilai_payment' => $pData['nilai_payment'] ?? 0,
            ];

            if (isset($pData['id']) && $pData['id']) {
                Payment::where('id', $pData['id'])->update($data);
            } else {
                $data['project_id'] = $project->id;
                Payment::create($data);
            }
        }

        return response()->json(['message' => 'Payments synced successfully']);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204);
    }
}
