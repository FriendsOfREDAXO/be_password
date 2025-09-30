<?php

namespace FriendsOfRedaxo\BePassword\Services;

use rex_csrf_token;
use rex_fragment;

use function sprintf;

/**
 * RenderService basierend auf der Fragment-Klasse.
 *
 * Unterschied zu rex_fragment:
 * - Die möglichen Variablen/Werte sind eng gefasst. Je Element, das in den
 *   Fragmenten abgerufen werden kann, gibt es einen getter und setter.
 *
 * - Die Fragmente werden in fragments/be_password gesucht
 * - Die Klasse hat eine factory-Methode
 * - die Setter können verkettet werden
 */
class RenderService extends rex_fragment
{
    final public function __construct()
    {
        parent::__construct();
    }

    /**
     * @api
     */
    public static function factory(): static
    {
        return new static();
    }

    /**
     * @api
     */
    public function setErrorMsg(string $msg): self
    {
        $this->setVar('errorMessage', $msg, false);
        return $this;
    }

    /**
     * @api
     */
    public function setSuccessMsg(string $msg): self
    {
        $this->setVar('successMessage', $msg, false);
        return $this;
    }

    /**
     * @api
     */
    public function setCsrfToken(rex_csrf_token $token): self
    {
        $this->setVar('csrfToken', $token, false);
        return $this;
    }

    /**
     * @api
     */
    public function setToken(string $token): self
    {
        $this->setVar('successMessage', $token, false);
        return $this;
    }

    /**
     * @api
     */
    public function setEmail(string $email): self
    {
        $this->setVar('email', $email, false);
        return $this;
    }

    /**
     * @api
     */
    public function setShowForm(bool $showForm): self
    {
        $this->setVar('showForm', $showForm, false);
        return $this;
    }

    /**
     * @api
     */
    public function getErrorMsg(): string
    {
        return (string) $this->getVar('errorMessage', '');
    }

    /**
     * @api
     */
    public function getSuccessMsg(): string
    {
        return (string) $this->getVar('successMessage', '');
    }

    /**
     * @api
     */
    public function getCsrfTokenHtml(): string
    {
        /** @var rex_csrf_token|null $csrf_token */
        $csrf_token = $this->getVar('csrfToken', null);
        return is_a($csrf_token, rex_csrf_token::class) ? $csrf_token->getHiddenField() : '';
    }

    /**
     * @api
     */
    public function getEmail(): string
    {
        return (string) $this->getVar('email', '');
    }

    /**
     * @api
     */
    public function getToken(): string
    {
        return (string) $this->getVar('token', '');
    }

    /**
     * @api
     */
    public function getShowForm(): bool
    {
        return (bool) $this->getVar('showForm', false);
    }

    /**
     * @api
     * @param string $filename
     * @return string
     */
    public function parse($filename)
    {
        $filename = sprintf('be_password/%s', $filename);
        return parent::parse($filename);
    }
}
