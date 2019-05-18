<?php

namespace Drupal\event_countdown_service;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * EventCountdownService is a service for calculating number of days
 * before event starts
 */
class EventCountdownService {

    /**
     * Method which gets event date and displays message
     * how many days are left before event starts
     */
    public function getMessage(): ?string
    {
        $nodeId = null;
        $node   = \Drupal::routeMatch()->getParameter('node');
        
        if ($node instanceof \Drupal\node\NodeInterface) {
            $nodeId = $node->id();
        }

        if (!$nodeId) {
            return null;
        }

        $connection = \Drupal::database();

        try {
            $query = \Drupal::database()->select('node__field_event_date', 'n');
            $query->addField('n', 'field_event_date_value');
            $query->condition('n.entity_id', $nodeId);
            
            $result = $query->execute()->fetchField(0);

            if (!$result) {
                return null;
            }
            
            return $this->getNumberOfDays(new DrupalDateTime($result));
        } catch(\Exception $e) {
            return "Error occured. Please try again later.";
        }
    }

    /**
     * Calculates time difference and returns proper message
     */
    private function getNumberOfDays(DrupalDateTime $date): string
    {
        $today = new DrupalDateTime(date('Y-m-d h:i:s'));

        if ($today > $date) {
            return 'This event already passed.';
        }

        $timeDiff = $date->diff($today);

        if ($timeDiff->days === 0) {
            return 'This event is happening today.';
        }

        return sprintf('%d days left until event starts.', $timeDiff->days);
    }
}
