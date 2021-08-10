<?php

/* 
 * A class to represent an bootstrap button that will run some javascript action.
 */

class ViewBootstrapButton extends \Programster\AbstractView\AbstractView
{
    private string $m_type;
    private string $m_label;
    private bool $m_isDisabled;
    private string $m_onclick;
    
    
    /**
     * Create a link/anchor on the page that looks like a pretty button. This is not an actual <button> object which 
     * you should use if you wish to run some javascript.
     * @param ButtonType $type
     * @param string $label
     * @param string $onclick
     * @param bool $isDisabled
     */
    public function __construct(ButtonType $type, string $label, string $onclick, bool $isDisabled = false)
    {
        $this->m_onclick = $onclick;
        $this->m_isDisabled = $isDisabled;
        $this->m_type = $type;
        $this->m_label = $label;
    }
    
    
    protected function renderContent() 
    {
?>

<button 
    onclick="<?= $this->m_onclick; ?>" 
    type="button" 
    class="btn btn-<?= $this->m_type; ?>"
    <?= ($this->m_isDisabled) ? " disabled aria-disabled=\"true\" " : ""; ?>
><?= $this->m_label; ?></button>

<?php
    }

}
