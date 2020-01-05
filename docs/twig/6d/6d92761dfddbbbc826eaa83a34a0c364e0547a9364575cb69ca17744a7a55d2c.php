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

/* method.html.twig */
class __TwigTemplate_ba95df323c57d38dc3c91c48ff4bc0b524cc73c4a13778569f7f0f9dde6f5421 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["macros"] = $this->loadTemplate("base/macros.html.twig", "method.html.twig", 1)->unwrap();
        // line 2
        echo "
                            <div class=\"row collapse\">
                                <div class=\"detail-description\">
                                    <div class=\"long_description\">";
        // line 5
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["method"] ?? null), "description", [])]);
        echo "</div>

                                    <table class=\"table\">
                                        ";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "tags", []));
        foreach ($context['_seq'] as $context["_key"] => $context["tagList"]) {
            if (!twig_in_filter($this->getAttribute($this->getAttribute($context["tagList"], 0, []), "name", []), [0 => "param", 1 => "return", 2 => "api", 3 => "throws", 4 => "throw"])) {
                // line 9
                echo "                                            <tr>
                                                <th>
                                                    ";
                // line 11
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["tagList"], 0, []), "name", []), "html", null, true);
                echo "
                                                </th>
                                                <td>
                                                    ";
                // line 14
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tagList"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 15
                    echo "                                                        ";
                    if ((($this->getAttribute($context["tag"], "name", []) == "since") || "deprecated")) {
                        // line 16
                        echo "                                                            ";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                        echo "
                                                        ";
                    }
                    // line 18
                    echo "                                                        ";
                    if (($this->getAttribute($context["tag"], "name", []) == "see")) {
                        // line 19
                        echo "                                                            ";
                        echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", [])]);
                        echo "
                                                        ";
                    }
                    // line 21
                    echo "                                                        ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 23
                echo "                                                </td>
                                            </tr>
                                        ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tagList'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 26
        echo "                                        ";
        if (((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throws", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throw", [])) > 0))) {
            // line 27
            echo "                                            <tr>
                                                <th>Throws</th>
                                                <td>
                                                    <dl>
                                                    ";
            // line 31
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throws", []));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 32
                echo "                                                        <dt>";
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["exception"], "types", [])]), "|");
                echo "</dt>
                                                        <dd>";
                // line 33
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["exception"], "description", [])]);
                echo "</dd>
                                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 35
            echo "                                                    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throw", []));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 36
                echo "                                                        <dt>";
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["exception"], "types", [])]), "|");
                echo "</dt>
                                                        <dd>";
                // line 37
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["exception"], "description", [])]);
                echo "</dd>
                                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "                                                    </dl>
                                                </td>
                                            </tr>
                                        ";
        }
        // line 43
        echo "                                    </table>

                                    ";
        // line 45
        if ((twig_length_filter($this->env, $this->getAttribute(($context["method"] ?? null), "arguments", [])) > 0)) {
            // line 46
            echo "                                        <h3>Arguments</h3>
                                        ";
            // line 47
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "arguments", []));
            foreach ($context['_seq'] as $context["_key"] => $context["argument"]) {
                // line 48
                echo "                                            <div class=\"subelement argument\">
                                                <h4>";
                // line 49
                echo twig_escape_filter($this->env, $this->getAttribute($context["argument"], "name", []), "html", null, true);
                echo "</h4>
                                                <code>";
                // line 50
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["argument"], "types", [])]), "|");
                echo "</code><p>";
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["argument"], "description", [])]);
                echo "</p>
                                            </div>
                                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['argument'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "                                    ";
        }
        // line 54
        echo "
                                    ";
        // line 55
        if (($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", []) && (twig_join_filter($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", [])) != "void"))) {
            // line 56
            echo "                                        <h3>Response</h3>
                                        <code>";
            // line 57
            echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", [])]), "|");
            echo "</code><p>";
            echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "description", [])]);
            echo "</p>
                                    ";
        }
        // line 59
        echo "                                </div>
                            </div>
";
    }

    public function getTemplateName()
    {
        return "method.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  197 => 59,  190 => 57,  187 => 56,  185 => 55,  182 => 54,  179 => 53,  168 => 50,  164 => 49,  161 => 48,  157 => 47,  154 => 46,  152 => 45,  148 => 43,  142 => 39,  134 => 37,  129 => 36,  124 => 35,  116 => 33,  111 => 32,  107 => 31,  101 => 27,  98 => 26,  89 => 23,  80 => 21,  74 => 19,  71 => 18,  65 => 16,  62 => 15,  58 => 14,  52 => 11,  48 => 9,  43 => 8,  37 => 5,  32 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "method.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig/method.html.twig");
    }
}
