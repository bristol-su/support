<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* /index.html.twig */
class __TwigTemplate_3e3804c693684a10280a94b04caa699e193c2b60ce8c30979db73f9b723b2031 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'heroUnit' => [$this, 'block_heroUnit'],
            'content' => [$this, 'block_content'],
            'listRootNamespaces' => [$this, 'block_listRootNamespaces'],
            'listRootPackages' => [$this, 'block_listRootPackages'],
            'listCharts' => [$this, 'block_listCharts'],
            'listReports' => [$this, 'block_listReports'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["macros"] = $this->loadTemplate("base/macros.html.twig", "/index.html.twig", 3)->unwrap();
        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_heroUnit($context, array $blocks = [])
    {
        // line 6
        echo "    <div class=\"hero-unit\">
        <h1>";
        // line 7
        echo $this->getAttribute(($context["project"] ?? null), "name", []);
        echo "</h1>
        <h2>Documentation</h2>
    </div>
";
    }

    // line 12
    public function block_content($context, array $blocks = [])
    {
        // line 13
        echo "    <div class=\"row\">
        <div class=\"span7\">
            ";
        // line 15
        if (((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "namespaces", [])) > 0) ||  !$this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "packages", []))) {
            // line 16
            echo "                <div class=\"well\">
                    <ul class=\"nav nav-list\">
                        <li class=\"nav-header\">Namespaces</li>
                        ";
            // line 19
            $this->displayBlock('listRootNamespaces', $context, $blocks);
            // line 25
            echo "                    </ul>
                </div>
            ";
        }
        // line 28
        echo "
            ";
        // line 29
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "packages", [])) > 0)) {
            // line 30
            echo "                <div class=\"well\">
                    <ul class=\"nav nav-list\">
                        <li class=\"nav-header\">Packages</li>
                        ";
            // line 33
            $this->displayBlock('listRootPackages', $context, $blocks);
            // line 39
            echo "                    </ul>
                </div>
            ";
        }
        // line 42
        echo "
        </div>
        <div class=\"span5\">
            <div class=\"well\">
                <ul class=\"nav nav-list\">
                    <li class=\"nav-header\">Charts</li>
                    ";
        // line 48
        $this->displayBlock('listCharts', $context, $blocks);
        // line 51
        echo "                </ul>
            </div>
            <div class=\"well\">
                <ul class=\"nav nav-list\">
                    <li class=\"nav-header\">Reports</li>
                    ";
        // line 56
        $this->displayBlock('listReports', $context, $blocks);
        // line 73
        echo "                </ul>
            </div>
        </div>
    </div>
";
    }

    // line 19
    public function block_listRootNamespaces($context, array $blocks = [])
    {
        // line 20
        echo "                            <li><a href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [$this->getAttribute(($context["project"] ?? null), "namespace", [])]), "html", null, true);
        echo "\">Global (";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "namespace", []), "name", []), "html", null, true);
        echo ")</a></li>
                            ";
        // line 21
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["project"] ?? null), "namespace", []), "children", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
            // line 22
            echo "                                <li><a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [$context["namespace"]]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["namespace"], "name", []), "html", null, true);
            echo "</a></li>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 24
        echo "                        ";
    }

    // line 33
    public function block_listRootPackages($context, array $blocks = [])
    {
        // line 34
        echo "                            <li><a href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [twig_first($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "packages", []))]), "html", null, true);
        echo "\">Global (";
        echo twig_escape_filter($this->env, $this->getAttribute(twig_first($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "packages", [])), "name", []), "html", null, true);
        echo ")</a></li>
                            ";
        // line 35
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(twig_first($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "packages", [])), "children", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["package"]) {
            // line 36
            echo "                                <li><a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [$context["package"]]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["package"], "name", []), "html", null, true);
            echo "</a></li>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['package'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        echo "                        ";
    }

    // line 48
    public function block_listCharts($context, array $blocks = [])
    {
        // line 49
        echo "                        <li><a href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["graph_class.html"]), "html", null, true);
        echo "\"><i class=\"icon-list-alt\"></i> Class inheritance diagram</a></li>
                    ";
    }

    // line 56
    public function block_listReports($context, array $blocks = [])
    {
        // line 57
        echo "                        <li>
                            <a href=\"";
        // line 58
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["errors.html"]), "html", null, true);
        echo "\">
                                <i class=\"icon-list-alt\"></i> Errors ";
        // line 59
        echo $this->getAttribute(($context["macros"] ?? null), "renderErrorCounter", [0 => $this->getAttribute(($context["project"] ?? null), "files", [])], "method");
        echo "
                            </a>
                        </li>
                        <li>
                            <a href=\"";
        // line 63
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["deprecated.html"]), "html", null, true);
        echo "\">
                                <i class=\"icon-list-alt\"></i> Deprecated ";
        // line 64
        echo $this->getAttribute(($context["macros"] ?? null), "renderDeprecatedCounter", [0 => $this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "elements", [])], "method");
        echo "
                            </a>
                        </li>
                        <li>
                            <a href=\"";
        // line 68
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["markers.html"]), "html", null, true);
        echo "\">
                                <i class=\"icon-list-alt\"></i> Markers ";
        // line 69
        echo $this->getAttribute(($context["macros"] ?? null), "renderMarkerCounter", [0 => $this->getAttribute(($context["project"] ?? null), "files", [])], "method");
        echo "
                            </a>
                        </li>
                    ";
    }

    public function getTemplateName()
    {
        return "/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  226 => 69,  222 => 68,  215 => 64,  211 => 63,  204 => 59,  200 => 58,  197 => 57,  194 => 56,  187 => 49,  184 => 48,  180 => 38,  169 => 36,  165 => 35,  158 => 34,  155 => 33,  151 => 24,  140 => 22,  136 => 21,  129 => 20,  126 => 19,  118 => 73,  116 => 56,  109 => 51,  107 => 48,  99 => 42,  94 => 39,  92 => 33,  87 => 30,  85 => 29,  82 => 28,  77 => 25,  75 => 19,  70 => 16,  68 => 15,  64 => 13,  61 => 12,  53 => 7,  50 => 6,  47 => 5,  42 => 1,  40 => 3,  34 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/index.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig//index.html.twig");
    }
}
