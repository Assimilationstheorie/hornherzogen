<?php

namespace hornherzogen;

class AdminHelper
{
    const FALLBACK_USER = "none";
    private $config;
    private $formHelper;

    public function __construct()
    {
        $this->config = new ConfigurationWrapper;
        $this->formHelper = new FormHelper();
    }

    public function showUserLoggedIn()
    {
        $user = $this->getUserName();

        if (boolval($this->isAdmin())) {
            return '<span class="glyphicon glyphicon-user" style="color: red;"></span> ' . $user . '</a>';
        }
        return '<span class="glyphicon glyphicon-user"></span> ' . $user . '</a>';
    }

    public function getUserName()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return trim($_SERVER['PHP_AUTH_USER']);
        }
        return self::FALLBACK_USER;
    }

    public function isAdmin()
    {
        if (NULL != $this->config->superuser() && strpos($this->getUserName(), $this->config->superuser()) !== FALSE) {
            return true;
        }
        return false;
    }

    public function showLogoutMenu()
    {
        if (self::FALLBACK_USER != $this->getUserName()) {
            return '<li><a href="./logout.php"><span class="glyphicon glyphicon-erase"></span> Logout</a></li>';
        }
        return '<li><a href="#"><span class="glyphicon glyphicon-lamp"></span> Not logged in</a></li>';
    }

    public function showSuperUserMenu()
    {
        // TODO add submenu from index_old.php with paragraphs
        // see example at http://getbootstrap.com/examples/navbar/
        if ($this->isAdmin() || $this->getHost() == 'localhost') {
            return "
                <li class=\"dropdown\">
                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-road\"></span> Superadmin-Menu<span class=\"caret\"></span></a>
                <ul class=\"dropdown-menu\">
                  <li><a href=\"#\">Action</a></li>
                  <li><a href=\"#\">Another action</a></li>
                  <li><a href=\"#\">Something else here</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li class=\"dropdown-header\">Nav header</li>
                  <li><a href=\"#\">Separated link</a></li>
                  <li><a href=\"#\">One more separated link</a></li>
                </ul>
              </li>
";
        }
        return "<li><a href=\"#\"><span class=\"glyphicon glyphicon-road\"></span>No Superadmin-Menu</a></li>";
    }

    public function getHost()
    {
        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, 'SERVER_NAME')) {
            return trim($_SERVER['SERVER_NAME']);
        }
        // to avoid DNS manipulation to get superadmin access
        return NULL;
    }

    /**
     * @return string this page's URL with protocol and port.
     */
    function thisPageUrl()
    {
        $pageURL = 'http';
        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, "HTTPS") && $_SERVER["HTTPS"] === "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, "SERVER_PORT") && $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $this->getHost() . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            return $pageURL;
        }

        $pageURL .= $this->getHost() . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }

}
