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

/* base/macros.html.twig */
class __TwigTemplate_10e1b62eaa8439e021a4332493bb77a97d52671763850743f41c277f934c0b1f extends \Twig\Template
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
        // line 8
        echo "
";
        // line 16
        echo "
";
        // line 24
        echo "
";
    }

    // line 1
    public function getrenderMarkerCounter($__files__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "files" => $__files__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 2
            echo "    ";
            $context["count"] = 0;
            // line 3
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["files"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                // line 4
                echo "        ";
                $context["count"] = (($context["count"] ?? null) + twig_length_filter($this->env, $this->getAttribute($context["file"], "markers", [])));
                // line 5
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 6
            echo "    <span class=\"label label-info\">";
            echo twig_escape_filter($this->env, ($context["count"] ?? null), "html", null, true);
            echo "</span>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 9
    public function getrenderDeprecatedCounter($__elements__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "elements" => $__elements__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 10
            echo "    ";
            $context["count"] = 0;
            // line 11
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["elements"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                if ($this->getAttribute($context["element"], "deprecated", [])) {
                    // line 12
                    echo "        ";
                    $context["count"] = (($context["count"] ?? null) + 1);
                    // line 13
                    echo "    ";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 14
            echo "    <span class=\"label label-info\">";
            echo twig_escape_filter($this->env, ($context["count"] ?? null), "html", null, true);
            echo "</span>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 17
    public function getrenderErrorCounter($__files__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "files" => $__files__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 18
            echo "    ";
            $context["count"] = 0;
            // line 19
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["files"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                // line 20
                echo "        ";
                $context["count"] = (($context["count"] ?? null) + twig_length_filter($this->env, $this->getAttribute($context["file"], "allerrors", [])));
                // line 21
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 22
            echo "    <span class=\"label label-info\">";
            echo twig_escape_filter($this->env, ($context["count"] ?? null), "html", null, true);
            echo "</span>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 25
    public function getbuildBreadcrumb($__element__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "element" => $__element__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 26
            echo "    ";
            $context["self"] = $this;
            // line 27
            echo "
    ";
            // line 28
            if (($this->getAttribute(($context["element"] ?? null), "parentNamespace", []) && ($this->getAttribute($this->getAttribute(($context["element"] ?? null), "parentNamespace", []), "name", []) != "\\"))) {
                // line 29
                echo "        ";
                echo $context["self"]->getbuildBreadcrumb($this->getAttribute(($context["element"] ?? null), "parentNamespace", []));
                echo "
    ";
            }
            // line 31
            echo "
    <li><span class=\"divider\">\\</span><a href=\"";
            // line 32
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "</a></li>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "base/macros.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  213 => 32,  210 => 31,  204 => 29,  202 => 28,  199 => 27,  196 => 26,  184 => 25,  166 => 22,  160 => 21,  157 => 20,  152 => 19,  149 => 18,  137 => 17,  119 => 14,  112 => 13,  109 => 12,  103 => 11,  100 => 10,  88 => 9,  70 => 6,  64 => 5,  61 => 4,  56 => 3,  53 => 2,  41 => 1,  36 => 24,  33 => 16,  30 => 8,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "base/macros.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig/base/macros.html.twig");
    }
}
