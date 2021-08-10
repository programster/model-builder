<?php

/* 
 * A class to represent a bootstrap button.
 * https://getbootstrap.com/docs/4.0/components/buttons/
 */

class ViewBootstrapButtonLink extends \Programster\AbstractView\AbstractView
{
    private string $m_type;
    private string $m_label;
    private bool $m_isDisabled;
    private string $m_href;
    
    
    /**
     * Create a link/anchor on the page that looks like a pretty button. This is not an actual <button> object which 
     * you should use if you wish to run some javascript.
     * @param ButtonType $type
     * @param string $label
     * @param string $href
     * @param bool $isDisabled
     */
    public function __construct(ButtonType $type, string $label, string $href, bool $isDisabled = false)
    {
        $this->m_href = $href;
        $this->m_isDisabled = $isDisabled;
        $this->m_type = $type;
        $this->m_label = $label;
    }
    
    
    protected function renderContent() 
    {
?>

<a 
    href="<?= $this->m_href; ?>" 
    type="button" 
    class="btn btn-<?= $this->m_type; ?>"
    <?= ($this->m_isDisabled) ? " disabled aria-disabled=\"true\" " : ""; ?>
><?= $this->m_label; ?></a>

<?php
    }

}
