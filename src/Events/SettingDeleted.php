<?php

declare(strict_types=1);

namespace Cortex\Settings\Events;

use Cortex\Settings\Models\Setting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SettingDeleted implements ShouldBroadcast
{
    use InteractsWithSockets;
    use Dispatchable;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Cortex\Settings\Models\Setting
     */
    public Setting $model;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Settings\Models\Setting $setting
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting->withoutRelations();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('cortex.settings.settings.index'),
            new PrivateChannel("cortex.settings.settings.{$this->model->getRouteKey()}"),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'setting.deleted';
    }
}
