<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Announcement;
use Illuminate\Support\Facades\Mail;
use App\Models\UserLogin;

class SendAnnouncementEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:announcement-emails {announcementId}';
        
}
