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
    public static function factory(): static
    {
        return new static();
    }

    public function setErrorMsg(string $msg): self
    {
        $this->setVar('errorMessage', $msg, false);
        return $this;
    }

    public function setSuccessMsg(string $msg): self
    {
        $this->setVar('successMessage', $msg, false);
        return $this;
    }

    public function setCsrfToken(rex_csrf_token $token): self
    {
        $this->setVar('csrfToken', $token, false);
        return $this;
    }

    public function setToken(string $token): self
    {
        $this->setVar('successMessage', $token, false);
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->setVar('email', $email, false);
        return $this;
    }

    public function setShowForm(bool $showForm): self
    {
        $this->setVar('showForm', $showForm, false);
        return $this;
    }

    public function getErrorMsg(): string
    {
        return (string) $this->getVar('errorMessage', '');
    }

    public function getSuccessMsg(): string
    {
        return (string) $this->getVar('successMessage', '');
    }

    public function getCsrfTokenHtml(): string
    {
        /** @var rex_csrf_token $csrf_token */
        $csrf_token = $this->getVar('csrfToken', null);
        return is_a($csrf_token, rex_csrf_token::class) ? $csrf_token->getHiddenField() : '';
    }

    public function getEmail(): string
    {
        return (string) $this->getVar('email', '');
    }

    public function getToken(): string
    {
        return (string) $this->getVar('token', '');
    }

    public function getShowForm(): bool
    {
        return (bool) $this->getVar('showForm', false);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function parse($filename)
    {
        $filename = sprintf('be_password/%s', $filename);
        return parent::parse($filename);
    }
}
