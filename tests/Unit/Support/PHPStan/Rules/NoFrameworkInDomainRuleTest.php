<?php

declare(strict_types=1);

namespace Tests\Unit\Support\PHPStan\Rules;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PHPStan\Rules\NoFrameworkInDomainRule;
use Illuminate\Support\Str;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<NoFrameworkInDomainRule>
 */
final class NoFrameworkInDomainRuleTest extends RuleTestCase
{
    private const string FIXTURES = __DIR__.'/../../../../Fixtures/PHPStan/app/Core/Fake';

    private const int IMPORT_LINE = 7;

    protected function getRule(): Rule
    {
        return new NoFrameworkInDomainRule();
    }

    public function test_reports_illuminate_import_inside_context_domain(): void
    {
        $this->analyse(
            [self::FIXTURES.'/Domain/DomainUsingIlluminate.php'],
            [[$this->errorFor(Str::class), self::IMPORT_LINE]],
        );
    }

    public function test_reports_illuminate_import_inside_nested_domain_folder(): void
    {
        $this->analyse(
            [self::FIXTURES.'/Domain/Entity/NestedDomainUsingIlluminate.php'],
            [[$this->errorFor(Str::class), self::IMPORT_LINE]],
        );
    }

    public function test_reports_eloquent_model_import_inside_context_domain(): void
    {
        $this->analyse(
            [self::FIXTURES.'/Domain/DomainUsingAppModels.php'],
            [[$this->errorFor(User::class), self::IMPORT_LINE]],
        );
    }

    public function test_reports_http_layer_import_inside_context_domain(): void
    {
        $this->analyse(
            [self::FIXTURES.'/Domain/DomainUsingAppHttp.php'],
            [[$this->errorFor(Controller::class), self::IMPORT_LINE]],
        );
    }

    public function test_allows_framework_free_domain(): void
    {
        $this->analyse([self::FIXTURES.'/Domain/CleanDomain.php'], []);
    }

    public function test_allows_illuminate_import_in_application_layer(): void
    {
        $this->analyse([self::FIXTURES.'/Application/ApplicationUsingIlluminate.php'], []);
    }

    public function test_allows_illuminate_import_in_infra_layer(): void
    {
        $this->analyse([self::FIXTURES.'/Infra/InfraUsingIlluminate.php'], []);
    }

    public function test_allows_illuminate_import_in_infra_subfolder_named_domain(): void
    {
        $this->analyse([self::FIXTURES.'/Infra/Domain/InfraSubfolderNamedDomainUsingIlluminate.php'], []);
    }

    private function errorFor(string $import): string
    {
        return sprintf('❌ Domain layer cannot depend on framework class: %s', $import);
    }
}
