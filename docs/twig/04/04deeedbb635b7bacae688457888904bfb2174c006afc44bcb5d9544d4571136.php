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

/* /namespace.html.twig */
class __TwigTemplate_de526e81cdbc60a099aaddd80d716ed04e7dc0160c3688806c3ccb4db264ce29 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'hierarchy_element' => [$this, 'block_hierarchy_element'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html.twig", "/namespace.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        // line 4
        echo "    <section class=\"row-fluid\">
        <div class=\"span2 sidebar\">
            ";
        // line 6
        $context["namespace"] = $this->getAttribute(($context["project"] ?? null), "namespace", []);
        // line 7
        echo "            ";
        $this->displayBlock("sidebarNamespaces", $context, $blocks);
        echo "
        </div>
    </section>
    <section class=\"row-fluid\">
        <div class=\"span10 offset2\">
            <div class=\"row-fluid\">
                <div class=\"span8 content namespace\">
                    <nav>
                        ";
        // line 16
        echo "                        ";
        echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "parent", [])]);
        echo "
                        ";
        // line 18
        echo "                    </nav>
                    <h1><small>";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []), "html", null, true);
        echo "</small>";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "</h1>

                    ";
        // line 21
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "children", [])) > 0)) {
            // line 22
            echo "                    <h2>Namespaces</h2>
                    <table class=\"table table-hover\">
                        ";
            // line 24
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "children", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
                // line 25
                echo "                            <tr>
                                <td>";
                // line 26
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["namespace"], "class:short"]);
                echo "</td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "                    </table>
                    ";
        }
        // line 31
        echo "
                    ";
        // line 32
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0)) {
            // line 33
            echo "                    <h2>Traits</h2>
                    <table class=\"table table-hover\">
                        ";
            // line 35
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "traits", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["trait"]) {
                // line 36
                echo "                            <tr>
                                <td>";
                // line 37
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["trait"], "class:short"]);
                echo "</td>
                                <td><em>";
                // line 38
                echo twig_escape_filter($this->env, $this->getAttribute($context["trait"], "summary", []), "html", null, true);
                echo "</em></td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trait'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 41
            echo "                    </table>
                    ";
        }
        // line 43
        echo "
                    ";
        // line 44
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) {
            // line 45
            echo "                    <h2>Interfaces</h2>
                    <table class=\"table table-hover\">
                        ";
            // line 47
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "interfaces", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 48
                echo "                            <tr>
                                <td>";
                // line 49
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["interface"], "class:short"]);
                echo "</td>
                                <td><em>";
                // line 50
                echo twig_escape_filter($this->env, $this->getAttribute($context["interface"], "summary", []), "html", null, true);
                echo "</em></td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "                    </table>
                    ";
        }
        // line 55
        echo "
                    ";
        // line 56
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0)) {
            // line 57
            echo "                    <h2>Classes</h2>
                    <table class=\"table table-hover\">
                    ";
            // line 59
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "classes", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 60
                echo "                        <tr>
                            <td>";
                // line 61
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["class"], "class:short"]);
                echo "</td>
                            <td><em>";
                // line 62
                echo twig_escape_filter($this->env, $this->getAttribute($context["class"], "summary", []), "html", null, true);
                echo "</em></td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 65
            echo "                    </table>
                    ";
        }
        // line 67
        echo "                </div>

                <aside class=\"span4 detailsbar\">
                    <dl>
                        <dt>Namespace hierarchy</dt>
                        <dd class=\"hierarchy\">
                            ";
        // line 73
        $context["namespace"] = $this->getAttribute(($context["node"] ?? null), "parent", []);
        // line 74
        echo "                            ";
        $this->displayBlock('hierarchy_element', $context, $blocks);
        // line 82
        echo "                            <div class=\"namespace-wrapper\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "fullyQualifiedStructuralElementName", []), "html", null, true);
        echo "</div>
                        </dd>
                    </dl>
                </aside>
            </div>

            ";
        // line 88
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0)) {
            // line 89
            echo "            <div class=\"row-fluid\">
                <section class=\"span8 content namespace\">
                    <h2>Constants</h2>
                </section>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 96
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "constants", [])]));
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
            foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                // line 97
                echo "                    ";
                $this->displayBlock("constant", $context, $blocks);
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 99
            echo "            ";
        }
        // line 100
        echo "
            ";
        // line 101
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) {
            // line 102
            echo "            <div class=\"row-fluid\">
                <section class=\"span8 content namespace\">
                    <h2>Functions</h2>
                </section>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 109
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "functions", [])]));
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
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                // line 110
                echo "                    ";
                $this->displayBlock("method", $context, $blocks);
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 112
            echo "            ";
        }
        // line 113
        echo "
        </div>
    </section>
";
    }

    // line 74
    public function block_hierarchy_element($context, array $blocks = [])
    {
        // line 75
        echo "                                ";
        if (($context["namespace"] ?? null)) {
            // line 76
            echo "                                    ";
            $context["child"] = ($context["namespace"] ?? null);
            // line 77
            echo "                                    ";
            $context["namespace"] = $this->getAttribute(($context["namespace"] ?? null), "parent", []);
            // line 78
            echo "                                    ";
            $this->displayBlock("hierarchy_element", $context, $blocks);
            echo "
                                    <div class=\"namespace-wrapper\">";
            // line 79
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [($context["child"] ?? null)]);
            echo "</div>
                                ";
        }
        // line 81
        echo "                            ";
    }

    public function getTemplateName()
    {
        return "/namespace.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  352 => 81,  347 => 79,  342 => 78,  339 => 77,  336 => 76,  333 => 75,  330 => 74,  323 => 113,  320 => 112,  303 => 110,  286 => 109,  277 => 102,  275 => 101,  272 => 100,  269 => 99,  252 => 97,  235 => 96,  226 => 89,  224 => 88,  214 => 82,  211 => 74,  209 => 73,  201 => 67,  197 => 65,  188 => 62,  184 => 61,  181 => 60,  177 => 59,  173 => 57,  171 => 56,  168 => 55,  164 => 53,  155 => 50,  151 => 49,  148 => 48,  144 => 47,  140 => 45,  138 => 44,  135 => 43,  131 => 41,  122 => 38,  118 => 37,  115 => 36,  111 => 35,  107 => 33,  105 => 32,  102 => 31,  98 => 29,  89 => 26,  86 => 25,  82 => 24,  78 => 22,  76 => 21,  69 => 19,  66 => 18,  61 => 16,  49 => 7,  47 => 6,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/namespace.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//namespace.html.twig");
    }
}
