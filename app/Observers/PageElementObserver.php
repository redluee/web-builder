<?php

namespace App\Observers;

use App\Models\PageElement;
use App\Models\PageTextblock;

class PageElementObserver
{
    /**
     * Handle the PageElement "created" event.
     */
    public function created(PageElement $pageElement): void
    {
        $this->saved($pageElement);
    }

    /**
     * Handle the PageElement "updated" event.
     */
    public function updated(PageElement $pageElement): void
    {
        $this->saved($pageElement);
    }

    /**
     * Handle the PageElement "deleted" event.
     */
    public function deleted(PageElement $pageElement): void
    {
        $this->deleting($pageElement);
    }

    /**
     * Handle the PageElement "restored" event.
     */
    public function restored(PageElement $pageElement): void
    {
        $this->saved($pageElement);
    }

    /**
     * Handle the PageElement "force deleted" event.
     */
    public function forceDeleted(PageElement $pageElement): void
    {
        $this->deleting($pageElement);
    }

    public function saved(PageElement $pageElement): void
    {
        $config = $pageElement->settings;

        if (!isset($config['textblock_id'])) {
            return; // No textblock used in this element
        }

        $textblockId = $config['textblock_id'];

        // Ensure the page_textblocks entry exists
        PageTextblock::updateOrCreate([
            'page_id' => $pageElement->page_id,
            'textblock_id' => $textblockId,
        ], [
            // You can also set an order or timestamp if needed
        ]);
    }

    public function deleting(PageElement $pageElement): void
    {
        $config = $pageElement->settings;

        if (!isset($config['textblock_id'])) {
            return;
        }

        PageTextblock::where('page_id', $pageElement->page_id)
            ->where('textblock_id', $config['textblock_id'])
            ->delete();
    }
}
