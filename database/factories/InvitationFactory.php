<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email_sent' => $this->faker->randomElement(['no' ,'yes']),
            'event_id' => Event::pluck('id')->random(),
            'invitee_id' => User::pluck('id')->random(),
            'created_by' => User::pluck('id')->random(),
        ];
    }
}
