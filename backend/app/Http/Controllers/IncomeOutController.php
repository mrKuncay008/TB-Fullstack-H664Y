<?php

namespace App\Http\Controllers;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Income as ModelsIncome;
use App\Models\Outcome as ModelsOutcome;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class IncomeOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = ModelsIncome::all();
        $outcomes = ModelsOutcome::all();

        return response()->json([
            'table_income' => $incomes,
            'table_outcome' => $outcomes,
        ]);
    }

    public function getIncome()
    {
        $incomes = ModelsIncome::all();

        return response()->json([
            'table_income' => $incomes,
        ]);
    }

    public function getOutcome()
    {
        $outcomes = ModelsOutcome::all();

        return response()->json([
            'table_outcome' => $outcomes,
        ]);
    }

    public function storeIncome(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'total' => 'required',
            'date_colmn' => 'required|date',
        ]);

        $total = str_replace('.', '', $validatedData['total']);

        $income = new ModelsIncome;
        $income->total = $total;
        $income->name = $validatedData['name'];
        $income->date_colmn = $validatedData['date_colmn'];
        $income->save();

        return response()->json([
            'success',
            'Income successfully created!'
        ]);
    }

    public function storeOutcome(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'total' => 'required',
            'date_colmn' => 'required|date',
        ]);


        $total = str_replace('.', '', $validatedData['total']);


        $income = new ModelsOutcome();
        $income->total = $total;
        $income->name = $validatedData['name'];
        $income->date_colmn = $validatedData['date_colmn'];
        $income->save();


        return response()->json([
            'message' => 'Outcome successfully created!',
            'data' => $income
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function showIncome($id)
    {
        $income = ModelsIncome::find($id);

        if ($income) {
            return response()->json([
                'success' => true,
                'message' => 'Data income berhasil diambil.',
                'data' => $income,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data income tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }


    public function showOutcome($id)
    {
        $outcome = ModelsOutcome::find($id);

        if ($outcome) {
            return response()->json([
                'success' => true,
                'message' => 'Data outcome berhasil diambil.',
                'data' => $outcome,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data outcome tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }

    public function updateIncome(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'total' => 'required',
            'date_colmn' => 'required|date',
        ]);

        // Menghilangkan titik pada total sebelum disimpan
        $validatedData['total'] = str_replace('.', '', $validatedData['total']);

        // Temukan data income berdasarkan ID
        $income = ModelsIncome::find($id);

        if ($income) {

            $income->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Data income berhasil diperbarui.',
                'data' => $income,
            ], 200); // HTTP 200 OK
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data income tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }


    public function updateOutcome(Request $request, string $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'total' => 'required',
            'date_colmn' => 'required|date',
        ]);

        // Menghilangkan titik pada total sebelum disimpan
        $validatedData['total'] = str_replace('.', '', $validatedData['total']);

        // Temukan data outcome berdasarkan ID
        $outcome = ModelsOutcome::find($id);

        if ($outcome) {

            $outcome->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Data outcome berhasil diperbarui.',
                'data' => $outcome,
            ], 200); // HTTP 200 OK
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data outcome tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroyIncome(string $id)
    {
        $income = ModelsIncome::find($id);

        if ($income) {

            $income->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data income berhasil dihapus.',
            ], 200); // HTTP 200 OK
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data income tidak ditemukan.',
            ], 404); // HTTP 404 Not Found
        }
    }

    public function getYears(Request $request)
    {
        $year = $request->query('year');

        // Jika ada parameter 'year', filter data berdasarkan tahun tersebut
        if ($year) {
            // Misalnya filter berdasarkan tahun dalam kolom 'date_colmn'
            $incomes = ModelsIncome::whereYear('date_colmn', $year)->get();
            $outcomes = ModelsOutcome::whereYear('date_colmn', $year)->get();
        } else {
            // Jika tidak ada parameter 'year', ambil semua data
            $incomes = ModelsIncome::all();
            $outcomes = ModelsOutcome::all();
        }

        // Ambil hanya tahun-tahun yang unik dari data 'date_colmn'
        $years = ModelsIncome::selectRaw('YEAR(date_colmn) as year')
            ->union(
                ModelsOutcome::selectRaw('YEAR(date_colmn) as year')
            )
            ->distinct()
            ->get();

        // Mengembalikan data tahun yang ditemukan
        return response()->json($years);
    }

    public function exportExcel(Request $request)
    {

        $year = $request->query('year');

        // Ambil data berdasarkan tahun yang dipilih
        $incomes = ModelsIncome::whereYear('date_colmn', $year)->get();
        $outcomes = ModelsOutcome::whereYear('date_colmn', $year)->get();

        $spreadsheet = new Spreadsheet();

        // Proses Income data
        $incomeSheet = $spreadsheet->setActiveSheetIndex(0);
        $incomeSheet->setTitle("Incomes - $year");
        $incomeSheet->setCellValue('A1', 'Name');
        $incomeSheet->setCellValue('B1', 'Date');
        $incomeSheet->setCellValue('C1', 'Total');

        foreach ($incomes as $index => $income) {
            $incomeSheet->setCellValue('A' . ($index + 2), $income->name);
            $incomeSheet->setCellValue('B' . ($index + 2), $income->date_colmn);
            $incomeSheet->setCellValue('C' . ($index + 2), $income->total);
        }

        $spreadsheet->createSheet();
        $outcomeSheet = $spreadsheet->createSheet();
        $outcomeSheet->setTitle("Outcomes - $year");
        $outcomeSheet->setCellValue('A1', 'Name');
        $outcomeSheet->setCellValue('B1', 'Date');
        $outcomeSheet->setCellValue('C1', 'Total');

        foreach ($outcomes as $index => $outcome) {
            $outcomeSheet->setCellValue('A' . ($index + 2), $outcome->name);
            $outcomeSheet->setCellValue('B' . ($index + 2), $outcome->date_colmn);
            $outcomeSheet->setCellValue('C' . ($index + 2), $outcome->total);
        }

        $fileName = "Income_Outcome_{$year}.xlsx";
        $filePath = storage_path("app/public/{$fileName}");

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function destroyOutcome(string $id)
    {
        $income = ModelsOutcome::find($id);

        if ($income) {
            $income->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data income berhasil dihapus.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data income tidak ditemukan.',
            ], 404);
        }
    }
    public function handleChat(Request $request)
    {
        $userInput = strtolower($request->input('message'));

        // Menentukan tanggal
        $currentYear = now()->year;
        $currentMonth = now();
        $formattedCurrentMonth = $currentMonth->translatedFormat('F Y');
        $previousMonth = now()->subMonth()->translatedFormat('F Y');
        $nextYear = $currentYear + 1;

        // Ambil data pendapatan dan pengeluaran
        $yearlyIncome = ModelsIncome::whereYear('date_colmn', $currentYear)->sum('total');
        $yearlyOutcome = ModelsOutcome::whereYear('date_colmn', $currentYear)->sum('total');
        $monthlyIncome = ModelsIncome::whereYear('date_colmn', $currentYear)
            ->whereMonth('date_colmn', $currentMonth->month)
            ->sum('total');
        $monthlyOutcome = ModelsOutcome::whereYear('date_colmn', $currentYear)
            ->whereMonth('date_colmn', $currentMonth->month)
            ->sum('total');

        // Mengumpulkan data pendapatan dan pengeluaran per tahun
        $startYear = ModelsIncome::min('date_colmn');
        $startYear = $startYear ? \Carbon\Carbon::parse($startYear)->year : $currentYear;

        $annualData = [];
        for ($year = $startYear; $year <= $nextYear; $year++) {
            $income = ModelsIncome::whereYear('date_colmn', $year)->sum('total');
            $outcome = ModelsOutcome::whereYear('date_colmn', $year)->sum('total');

            if ($income > 0 || $outcome > 0 || $year <= $currentYear) {
                $annualData[] = [
                    'year' => $year,
                    'income' => $income,
                    'outcome' => $outcome,
                ];
            }
        }

        // Menghitung total kotor, total bersih, dan saldo
        $totalGrossIncome = array_sum(array_column($annualData, 'income'));
        $totalGrossOutcome = array_sum(array_column($annualData, 'outcome'));
        $balance = $totalGrossIncome - $totalGrossOutcome;

        // Format informasi
        $annualSummary = '';
        foreach ($annualData as $data) {
            $annualSummary .= "Pada tahun {$data['year']}, pendapatan adalah Rp " . number_format($data['income'], 2, ',', '.') .
                " dan pengeluaran adalah Rp " . number_format($data['outcome'], 2, ',', '.') . ".\n";
        }

        $totalSummary = "Total pendapatan kotor: Rp " . number_format($totalGrossIncome, 2, ',', '.') . "\n" .
            "Total pengeluaran: Rp " . number_format($totalGrossOutcome, 2, ',', '.') . "\n" .
            "Saldo (balance): Rp " . number_format($balance, 2, ',', '.') . "\n";

        // Format knowledge untuk Gemini
        $stringKnowledge = "
            Berikut adalah informasi pendapatan dan pengeluaran yang dirangkum berdasarkan data yang ada:
            $annualSummary
    
            Untuk bulan $formattedCurrentMonth, pendapatan tercatat sebesar Rp " . number_format($monthlyIncome, 2, ',', '.') . "
            dan pengeluaran tercatat sebesar Rp " . number_format($monthlyOutcome, 2, ',', '.') . ".
        
            Untuk bulan sebelumnya ($previousMonth), data pendapatan dan pengeluaran juga dapat dibandingkan jika diperlukan.
        
            $totalSummary
    
            Parafrase informasi ini ketika user meminta informasi pendapatan dan pengeluaran dan jika user meminta informasi pendapatan dan pengeluaran per bulan dan balance juga.
        ";

        $chatHistoryArray = session()->get('chat_history', []);

        if (count($chatHistoryArray) == 0) {
            $chatHistoryArray[] = Content::parse(part: $stringKnowledge);
            $chat = Gemini::chat()->startChat($chatHistoryArray);

            $response = $chat->sendMessage($userInput);
            $chatHistoryArray[] = Content::parse(part: $response->text(), role: Role::MODEL);
            session()->put('chat_history', $chatHistoryArray);
        } else {
            $chat = Gemini::chat()->startChat($chatHistoryArray);
            $response = $chat->sendMessage($userInput);

            $chatHistoryArray[] = Content::parse(part: $userInput);
            $chatHistoryArray[] = Content::parse(part: $response->text(), role: Role::MODEL);

            session()->put('chat_history', $chatHistoryArray);
        }

        $responseText = $response->text();
        $responseTime = now()->format('H:i');

        return response()->json([
            'success' => true,
            'chat' => [
                'userMessage' => $userInput,
                'geminiResponse' => $responseText,
                'time' => $responseTime,
            ]
        ]);
    }


}
