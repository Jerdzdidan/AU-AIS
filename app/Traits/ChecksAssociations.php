<?php

namespace App\Traits;

trait ChecksAssociations
{
    /**
     * Define which relationships to check
     * Override this in your model
     */
    protected function getRelationshipsToCheck()
    {
        return [];
    }
    
    /**
     * Check if model has any related records before deletion
     */
    public function checkAssociations()
    {
        $associations = [];
        $relationships = $this->getRelationshipsToCheck();
        
        foreach ($relationships as $relation => $label) {
            if (method_exists($this, $relation)) {
                $count = $this->$relation()->count();
                
                if ($count > 0) {
                    $associations[] = [
                        'relation' => $relation,
                        'label' => $label,
                        'count' => $count
                    ];
                }
            }
        }
        
        if (empty($associations)) {
            return [
                'hasAssociations' => false,
                'message' => null,
                'details' => []
            ];
        }
        
        // Build message
        $parts = array_map(function($assoc) {
            return "{$assoc['count']} {$assoc['label']}";
        }, $associations);
        
        $message = 'Cannot delete. It has ' . implode(', ', $parts) . ' associated with it.';
        
        return [
            'hasAssociations' => true,
            'message' => $message,
            'details' => $associations
        ];
    }
}