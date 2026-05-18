<?php

namespace App\Tests;

use App\Services\PremiumMemberService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Activité 2 : Testez la classe PremiumMemberService
 * Doc des asserts de PHPUnit : https://docs.phpunit.de/en/13.1/assertions.html
 * Cette exercice est un peu plus dur et plus realiste.
 * Il s'agit de tester la classe PremiumMemberService qui contient des méthodes plus complexes que celles de GeometryService.
 * - La méthode generateMemberProfile génère un profil de membre à partir de son nom d'utilisateur, son âge et ses centres d'intérêt. Elle doit respecter plusieurs specifications que vous trouverez dans les commentaires de la méthode.
 * - La méthode applyPromoCode applique une réduction à un montant en fonction d'un code promo. Elle doit respecter plusieurs specifications que vous trouverez dans les commentaires de la méthode.
 * CERTAIN specification non pas été respectées dans l'implémentation de la classe PremiumMemberService, votre travail est de les identifier et de les tester correctement.
 * CERTAIN Test devrons donc échoué et c'est le but c'est la preuve que votre test et bien ecrit car il respecte la spec et pas juste l'implémentation.
 * C'est ce cette façon qu'on l'on évite d'écrire des test biasé.
 */
class PremiumMemberServiceTest extends KernelTestCase
{
    private PremiumMemberService $premiumMemberService;
    protected function setUp(): void
    {
        // Plutot que de faire new PremiumMemberService() on va le récuperer depuis le container de symfony pour être sur d'avoir la même instance 
        // que celle utilisée dans l'application c'est obligatoire pour des services plus réaliste qui inject des Repo ou d'autre Service par exemple.

        self::bootKernel();
        $this->premiumMemberService = static::getContainer()->get(PremiumMemberService::class);
    }
    // Remplissez les test restants :)
    // Bon courage héhé :)

    /**
     * Test la fonction generateMemberProfile pour un cas de SUCCES.
     * - assertIsArray
     * - assertArrayHasKey
     * - assertStringStartsWith
     * - assertSame : pour comparer deux tableaux associatifs
     * - assertMatchesRegularExpression
     * - Voir la doc pour les autres asserts : https://docs.phpunit.de/en/13.1/assertions.html
     */
    public function testGenerateMemberProfileSuccess(): void
    {
        $member = $this->premiumMemberService->generateMemberProfile("Arthur", 25, ['Coding', 'Gaming']);

        $this->assertIsArray($member);
        $this->assertArrayHasKey('id', $member);
    }

    /**
     * Test la fonction generateMemberProfile pour un cas d'ECHEC lorsque le nom d'utilisateur est vide.
     */
    public function testGenerateMemberProfileEmptyUsername(): void
    {
        // ExpectExeception prepart la levé d'exeption, pour les exeptions on utilise 
        // expect plutot que assert
        // Utilisez cette exemple pour tester les autres expections dans d'autre test.
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom d'utilisateur ne peut pas être vide.");
        $this->premiumMemberService->generateMemberProfile("", 25, ['Coding', 'Gaming']);
    }

    public function testGenerateMemberProfileThrowsExceptionForUnderage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le membre doit être majeur.");
        $this->premiumMemberService->generateMemberProfile("Arthur", 15, ['Coding', 'Gaming']);
    }

    public function testGenerateMemberProfileThrowsExceptionForEmptyUsername(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom d'utilisateur ne peut pas être vide.");
        $this->premiumMemberService->generateMemberProfile("", 25, ['Coding', 'Gaming']);
    }

    public function testApplyPromoCodeVip(): void
    {
        $finalamount = $this->premiumMemberService->applyPromoCode(10, 'VIP20');
        $this->assertEquals(8, $finalamount);
    }
    
    // On y est presque...

    public function testIsEligibleForUpgrade(): void
    {
        $iseligible = $this->premiumMemberService->isEligibleForUpgrade(25, ['Gaming', 'Code', 'Flower'], 105);
        $this->assertTrue($iseligible);
    }


    public function testApplyPromoCodeSummer50(): void
    {
        $finalamount = $this->premiumMemberService->applyPromoCode(10, 'SUMMER50');
        $this->assertEquals(5, $finalamount);
    }

    public function testApplyPromoCodeThrowExceptionInvalid(): void
    {
        $finalamount = $this->premiumMemberService->applyPromoCode(10, 'FALSE80');
        $this->assertEquals(10, $finalamount);
    }

    public function testApplyPromoCodeNullAmountUnchanged(): void
    {
        $finalamount = $this->premiumMemberService->applyPromoCode(10, '');
        $this->assertEquals(10, $finalamount);
    }

    public function testIsEligibleForUpgradeSuccess(): void
    {
        $iseligible = $this->premiumMemberService->isEligibleForUpgrade(25, ['Gaming', 'Code', 'Flower'], 105);
        $this->assertTrue($iseligible);
    }

    public function testIsEligibleForUpgradeUnderAge(): void
    {
        $iseligible = $this->premiumMemberService->isEligibleForUpgrade(15, ['Gaming', 'Code', 'Flower'], 105);
        $this->assertFalse($iseligible);
    }

    // C'est encore loin ? 8( 

    public function testIsEligibleForUpgradeInsufficientInterests(): void
    {
        $iseligible = $this->premiumMemberService->isEligibleForUpgrade(19, ['Gaming', 'Code'], 105);
        $this->assertFalse($iseligible);
    }

    public function testIsEligibleForUpgradeInsufficientSpent(): void
    {
        $iseligible = $this->premiumMemberService->isEligibleForUpgrade(25, ['Gaming', 'Code', 'Flower'], 85);
        $this->assertFalse($iseligible);
    }

    public function testCalculateLoyaltyPointsStandard(): void
    {
        $totalpoints = $this->premiumMemberService->calculateLoyaltyPoints(20);
        $this->assertEquals(200, $totalpoints);
    }

    public function testCalculateLoyaltyPointsPremium(): void
    {
        $totalpoints = $this->premiumMemberService->calculateLoyaltyPoints(20, true);
        $this->assertEquals(300, $totalpoints);
    }

    public function testCalculateLoyaltyPointsNegativeThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant ne peut pas être négatif.");
        $this->premiumMemberService->calculateLoyaltyPoints(-10, true);
    }

    public function testSummarizeSpending(): void
    {
        $spending = $this->premiumMemberService->summarizeSpending([20, 10, 60, 30]);
        $this->assertSame(['total' => 120, 'average' => 30, 'min' => 10, 'max' => 60], $spending);
    }

    public function testSummarizeSpendingEmptyThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le tableau de transactions ne peut pas être vide.");
        $this->premiumMemberService->summarizeSpending([]);
    }

    // On a presque fini :)

    public function testRenewSubscription1Month(): void
    {
        $addedsub = $this->premiumMemberService->renewSubscription(1);
        $prefix = date('Y-m-d', strtotime("+1 months"));
        $this->assertStringStartsWith($prefix, $addedsub);
    }

    public function testRenewSubscriptionInvalidDurationThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("La durée doit être de 1, 6 ou 12 mois.");
        $this->premiumMemberService->renewSubscription(3);
    }

    public function testAnonymizeProfile(): void
    {
        $profile = [
            'id' => 1,
            'meta' => [
                'username' => "Arthur",
                'clean_name' => "Rhahim",
                'age' => 25 
            ],
            'preferences' => [
                'interests' => ['Gaming', 'Code'],
                'count' => 2
            ]
        ];

        $anonym = [
            'id' => 1,
            'meta' => [
                'username' => "anonymous",
                'clean_name' => "anonymous",
                'age' => 0 
            ],
            'preferences' => [
                'interests' => [],
                'count' => 0
            ]
        ];

        $anonymized = $this->premiumMemberService->anonymizeProfile($profile);
        $this->assertSame($anonym, $anonymized);
    }

    public function testAnonymizeProfileInvalidThrowException(): void
    {
        $profile = [
            'meta' => [
                'username' => "Arthur",
                'clean_name' => "Rhahim",
                'age' => 25 
            ],
            'preferences' => [
                'interests' => ['Gaming', 'Code'],
                'count' => 2
            ]
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le profil est invalide ou incomplet.");
        $this->premiumMemberService->anonymizeProfile($profile);
    }
}
