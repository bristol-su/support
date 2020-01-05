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

/* base/class.sidebar.html.twig */
class __TwigTemplate_bd9111a895812ebab1218191d15f7fa1482666e4f39c72cd503ed2b6345c8501 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $_trait_0 = $this->loadTemplate("base/sidebar.html.twig", "base/class.sidebar.html.twig", 1);
        // line 1
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."base/sidebar.html.twig".'" cannot be used as a trait.', 1, $this->getSourceContext());
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            [
                'sidebar_buttons' => [$this, 'block_sidebar_buttons'],
                'sidebar_entry' => [$this, 'block_sidebar_entry'],
                'sidebar_content' => [$this, 'block_sidebar_content'],
            ]
        );
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 2
        echo "
";
        // line 3
        $this->displayBlock('sidebar_buttons', $context, $blocks);
        // line 15
        echo "
";
        // line 16
        $this->displayBlock('sidebar_entry', $context, $blocks);
        // line 23
        echo "
";
        // line 24
        $this->displayBlock('sidebar_content', $context, $blocks);
    }

    // line 3
    public function block_sidebar_buttons($context, array $blocks = [])
    {
        // line 4
        echo "    <div class=\"btn-group view pull-right\" data-toggle=\"buttons-radio\">
        <button class=\"btn details\" title=\"Show descriptions and method names\"><i class=\"icon-list\"></i></button>
        <button class=\"btn simple\" title=\"Show only method names\"><i class=\"icon-align-justify\"></i></button>
    </div>
    <div class=\"btn-group visibility\" data-toggle=\"buttons-checkbox\">
        <button class=\"btn public active\" title=\"Show public elements\">Public</button>
        <button class=\"btn protected\" title=\"Show protected elements\">Protected</button>
        <button class=\"btn private\" title=\"Show private elements\">Private</button>
        <button class=\"btn inherited active\" title=\"Show inherited elements\">Inherited</button>
    </div>
";
    }

    // line 16
    public function block_sidebar_entry($context, array $blocks = [])
    {
        // line 17
        echo "    <li class=\"";
        echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "visibility", []), "html", null, true);
        echo ((($this->getAttribute($this->getAttribute(($context["item"] ?? null), "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
        echo "\">
        <a href=\"#";
        // line 18
        echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
        echo "_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "name", []), "html", null, true);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "name", []), "html", null, true);
        echo " :: ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "summary", []), "html", null, true);
        echo "\">
            <span class=\"description\">";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "summary", []), "html", null, true);
        echo "</span><pre>";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["item"] ?? null), "name", []), "html", null, true);
        echo "</pre>
        </a>
    </li>
";
    }

    // line 24
    public function block_sidebar_content($context, array $blocks = [])
    {
        // line 25
        echo "    <ul class=\"side-nav nav nav-list\">
        <li class=\"nav-header\">
            <i class=\"icon-custom icon-method\"></i> Methods
            <ul>
                ";
        // line 29
        $context["specialMethods"] = (($this->getAttribute(($context["node"] ?? null), "magicMethods", [])) ? ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")) : ($this->getAttribute(($context["node"] ?? null), "inheritedMethods", [])));
        // line 30
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => ($context["specialMethods"] ?? null)], "method")]));
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
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 31
            echo "                    ";
            $context["type"] = "method";
            // line 32
            echo "                    ";
            if ((($this->getAttribute($context["item"], "visibility", []) == "") || ($this->getAttribute($context["item"], "visibility", []) == "public"))) {
                // line 33
                echo "                        ";
                $this->displayBlock("sidebar_entry", $context, $blocks);
                echo "
                    ";
            }
            // line 35
            echo "                ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "            </ul>
        </li>
        <li class=\"nav-header protected\">» Protected
            <ul>
                ";
        // line 40
        $context["specialMethods"] = (($this->getAttribute(($context["node"] ?? null), "magicMethods", [])) ? ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")) : ($this->getAttribute(($context["node"] ?? null), "inheritedMethods", [])));
        // line 41
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => ($context["specialMethods"] ?? null)], "method")]));
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            // line 42
            echo "                    ";
            if (($this->getAttribute($context["method"], "visibility", []) == "protected")) {
                // line 43
                echo "                        <li class=\"method ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "visibility", []), "html", null, true);
                echo ((($this->getAttribute($this->getAttribute($context["method"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
                echo "\">
                            <a href=\"#method_";
                // line 44
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo " :: ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "summary", []), "html", null, true);
                echo "\">
                                <span class=\"description\">";
                // line 45
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "summary", []), "html", null, true);
                echo "</span><pre>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "</pre>
                            </a>
                        </li>
                    ";
            }
            // line 49
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "            </ul>
        </li>
        <li class=\"nav-header private\">» Private
            <ul>
                ";
        // line 54
        $context["specialMethods"] = (($this->getAttribute(($context["node"] ?? null), "magicMethods", [])) ? ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")) : ($this->getAttribute(($context["node"] ?? null), "inheritedMethods", [])));
        // line 55
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => ($context["specialMethods"] ?? null)], "method")]));
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            // line 56
            echo "                    ";
            if (($this->getAttribute($context["method"], "visibility", []) == "private")) {
                // line 57
                echo "                        <li class=\"method ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "visibility", []), "html", null, true);
                echo ((($this->getAttribute($this->getAttribute($context["method"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
                echo "\">
                            <a href=\"#method_";
                // line 58
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo " :: ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "summary", []), "html", null, true);
                echo "\">
                                <span class=\"description\">";
                // line 59
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "summary", []), "html", null, true);
                echo "</span><pre>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "</pre>
                            </a>
                        </li>
                    ";
            }
            // line 63
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 64
        echo "            </ul>
        </li>
        <li class=\"nav-header\">
            <i class=\"icon-custom icon-constant\"></i> Constants
            <ul>
                ";
        // line 69
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["node"] ?? null), "constants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "inheritedConstants", [])], "method")]));
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 70
            echo "                <li class=\"constant ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "visibility", []), "html", null, true);
            echo "\">
                    <a href=\"#constant_";
            // line 71
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo " :: ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "summary", []), "html", null, true);
            echo "\">
                        <span class=\"description\">";
            // line 72
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "summary", []), "html", null, true);
            echo "</span><pre>";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo "</pre>
                    </a>
                </li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        echo "            </ul>
        </li>
    </ul>
";
    }

    public function getTemplateName()
    {
        return "base/class.sidebar.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  291 => 76,  279 => 72,  271 => 71,  266 => 70,  262 => 69,  255 => 64,  249 => 63,  240 => 59,  232 => 58,  226 => 57,  223 => 56,  218 => 55,  216 => 54,  210 => 50,  204 => 49,  195 => 45,  187 => 44,  181 => 43,  178 => 42,  173 => 41,  171 => 40,  165 => 36,  151 => 35,  145 => 33,  142 => 32,  139 => 31,  121 => 30,  119 => 29,  113 => 25,  110 => 24,  100 => 19,  90 => 18,  82 => 17,  79 => 16,  65 => 4,  62 => 3,  58 => 24,  55 => 23,  53 => 16,  50 => 15,  48 => 3,  45 => 2,  25 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "base/class.sidebar.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig/base/class.sidebar.html.twig");
    }
}
