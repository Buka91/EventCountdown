<?php

namespace Drupal\event_countdown_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides 'Event Countdown' Block.
 * @Block(
 *   id="event_countdown_block",
 *   admin_label = @Translation("Event Countdown Block"),
 *   category = @Translation("Countdown block")
 * )
 */
class EventCountdownBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build(): array
    {
        $blockOpts = ['#cache'  => ['max-age' => 0]];

        $message = $this->displayMessage();

        if ($message) {
            $blockOpts['#markup'] = $message;
        }

        return $blockOpts;
    }

    /**
     * Function for calculating countdown till event starts.
     */
    protected function displayMessage(): ?string
    {
        $countdownService = \Drupal::service('event_countdown_service.eventCountdown');
        
        return $countdownService->getMessage();
    }
}
