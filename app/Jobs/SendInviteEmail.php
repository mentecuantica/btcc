<?php

namespace Btcc\Jobs;

use Btcc\Jobs\Job;
use Btcc\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInviteEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = User::find(1);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::warning('Sleeping failed');
        sleep(10);

        $user = $this->user;
        $data = ['login'    => $user->email,
                 'password' => str_random(8),
        ];
        \Mail::send('emails.invite', $data, function ($message) use ($user) {
            $message->from('no-reply@btcc.vgt', 'Btcc');

            $message->to('calif@orni.aa');
        });
    }


    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        \Log::warning('SendInviteMail failed');
    }

}
