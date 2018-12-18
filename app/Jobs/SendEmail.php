<?php

namespace App\Jobs;

use App\Mail\CustomMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\MailingConfig;
use App\Models\NotificationTemplate;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $template;
    public $config;
    public $text;
    public $subject;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param MailingConfig $config
     */
    public function __construct(User $user, EmailTemplate $template)
    {
        $this->user = $user;
//        $this->config = $config;
        $this->template = $template;

        $this->prepareEmail();
        $this->saveLogs();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = (new CustomMail($this->subject, $this->text))
            ->onQueue('default')
            ->delay(Carbon::now()->addSecond());

        \Mail::to($this->user->primary_email)
            ->queue($message);
    }

    protected function saveLogs()
    {
        $log = new EmailLog([
            'user_id' => $this->user->id,
            'text' => $this->text
        ]);
        $log->save();

//        $this->config->last_fired = Carbon::now();
//        $this->config->save();

    }

    public function prepareEmail()
    {
        $this->text = self::processMailText($this->user);
        $this->subject = $this->template->subject;
    }

    public function processMailText(User $user, $payload = null)
    {
        $data = NotificationTemplate::flattenPayload($payload);

        $placeholders = [
            '{user.name}' => $user->name,
            '{user.full_name}' => $user->full_name,
            '{user.first_name}' => $user->first_name,
            '{user.last_name}' => $user->last_name,
            '{user.middle_name}' => $user->middle_name,
            '{user.animals.count}' => $user->animals->count(),
            '{user.animals_verified.count}' => $user->animalsVerified()->count(),
            '{user.animals_unverified.count}' => $user->animalsUnverified()->count(),
            '{animal.nickname}' => array_key_exists('nickname', $data) ? $data['nickname'] : '',
            '{animal.badge_num}' => array_key_exists('badge', $data) ? $data['badge'] : '',
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $this->template->body);
    }
}
