<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sessions')->delete();
        
        \DB::table('sessions')->insert(array (
            0 => 
            array (
                'id' => 'hi1MV031LvrN4TcdXv3bsIqaq1JQyow88PqzmRVL',
                'user_id' => NULL,
                'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0',
                'payload' => 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWVFoUjhlSkRRMjQxRzRkbjNhamtCVFVmMUYwVFBIWVBkREVmamtURiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9naW8taGFuZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6ODoiRGFuZ05oYXAiO2k6MTI7czo1OiJjaGVjayI7czoxOiIyIjtzOjg6Imdpb19oYW5nIjthOjE6e2k6OTthOjY6e3M6MTA6ImhpbmhfYW5oXzEiO3M6OToiZ2lheTcuanBnIjtzOjg6InRlbl9naWF5IjtzOjE1OiJQdW1hIE9uZSA1LjMgVFQiO3M6NzoiZG9uX2dpYSI7czo2OiI2OTkwMDAiO3M6ODoic29fbHVvbmciO2k6MTtzOjQ6InNpemUiO3M6MjoiNDUiO3M6MTA6ImtodXllbl9tYWkiO3M6MToiNSI7fX19',
                'last_activity' => 1763983237,
            ),
            1 => 
            array (
                'id' => 'kBKJX7IN05zKZXgED8iZMFqmfYst4uVcIgvRWUfV',
                'user_id' => NULL,
                'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0',
                'payload' => 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiQ2VCaEF1bVlVblR2ZGs0STBRakxYT1hEemRPSFFKTmNFTkFTa2RSeSI7czo4OiJnaW9faGFuZyI7YToxOntpOjE7YTo1OntzOjEwOiJoaW5oX2FuaF8xIjtzOjEwOiJnaWF5MTMuanBnIjtzOjg6InRlbl9naWF5IjtzOjY6Ik5NRCBSMiI7czo3OiJkb25fZ2lhIjtzOjc6IjEyMDAwMDAiO3M6ODoic29fbHVvbmciO2k6MTtzOjEwOiJraHV5ZW5fbWFpIjtzOjI6IjE1Ijt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvZ2lvLWhhbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6InN0YXRlIjtzOjQwOiI1UjBCWW5JamV4Q2p1RmZhc0Viek13cDlzYm9lY2xRSjRuRVdXV1RoIjtzOjg6IkRhbmdOaGFwIjtpOjEyO3M6NToiY2hlY2siO3M6MToiMiI7fQ==',
                'last_activity' => 1763956504,
            ),
        ));
        
        
    }
}