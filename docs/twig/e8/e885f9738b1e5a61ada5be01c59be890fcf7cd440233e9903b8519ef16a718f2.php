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

/* elements/namespaces.sidebar.html.twig */
class __TwigTemplate_0ab3c1c0cd0c9e3799759bfe693d1153892b47a8d221af990d3f775af906761b extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'sidebarNamespaces' => [$this, 'block_sidebarNamespaces'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('sidebarNamespaces', $context, $blocks);
    }

    public function block_sidebarNamespaces($context, array $blocks = [])
    {
        // line 2
        echo "    ";
        $context["code"] = twig_random($this->env);
        // line 3
        echo "    <div class=\"accordion\" style=\"margin-bottom: 0\">
        <div class=\"accordion-group\">
            <div class=\"accordion-heading\">
                ";
        // line 6
        if (((((twig_length_filter($this->env, $this->getAttribute(($context["namespace"] ?? null), "children", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute(($context["namespace"] ?? null), "classes", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["namespace"] ?? null), "interfaces", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["namespace"] ?? null), "traits", [])) > 0))) {
            // line 7
            echo "                    <a class=\"accordion-toggle ";
            echo ((($this->getAttribute(($context["namespace"] ?? null), "name", []) != "\\")) ? ("collapsed") : (""));
            echo "\" data-toggle=\"collapse\" data-target=\"#namespace-";
            echo twig_escape_filter($this->env, ($context["code"] ?? null), "html", null, true);
            echo "\"></a>
                ";
        }
        // line 9
        echo "                <a href=\"";
        echo call_user_func_array($this->env->getFilter('route')->getCallable(), [($context["namespace"] ?? null), "url"]);
        echo "\" style=\"margin-left: 30px; padding-left: 0\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["namespace"] ?? null), "name", []), "html", null, true);
        echo "</a>
            </div>
            <div id=\"namespace-";
        // line 11
        echo twig_escape_filter($this->env, ($context["code"] ?? null), "html", null, true);
        echo "\" class=\"accordion-body collapse ";
        echo ((($this->getAttribute(($context["namespace"] ?? null), "name", []) == "\\")) ? ("in") : (""));
        echo "\">
                <div class=\"accordion-inner\">

                    ";
        // line 14
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($context["namespace"], "children", [])]));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
            // line 15
            echo "                        ";
            $this->displayBlock("sidebarNamespaces", $context, $blocks);
            echo "
                    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "
                    <ul>
                        ";
        // line 19
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["namespace"] ?? null), "interfaces", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            // line 20
            echo "                            <li class=\"interface\">";
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["class"], "class:short"]);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "                        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["namespace"] ?? null), "traits", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            // line 23
            echo "                            <li class=\"trait\">";
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["class"], "class:short"]);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "                        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["namespace"] ?? null), "classes", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            // line 26
            echo "                            <li class=\"class\">";
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["class"], "class:short"]);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "                    </ul>
                </div>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "elements/namespaces.sidebar.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  150 => 28,  141 => 26,  136 => 25,  127 => 23,  122 => 22,  113 => 20,  109 => 19,  105 => 17,  88 => 15,  71 => 14,  63 => 11,  55 => 9,  47 => 7,  45 => 6,  40 => 3,  37 => 2,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "elements/namespaces.sidebar.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean/elements/namespaces.sidebar.html.twig");
    }
}
