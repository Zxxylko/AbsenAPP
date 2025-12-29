<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invite;
use Carbon\Carbon;

class CleanupInvites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invites:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus kode undangan yang sudah dipakai dan lebih dari 3 hari';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deleted = Invite::where('is_used', true)
                         ->where('created_at', '<', Carbon::now()->subDays(3))
                         ->delete();

        $this->info("Berhasil menghapus $deleted kode undangan.");
        return 0;
    }
}
