<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/mosque")
 * 
 */
class MosqueController extends Controller {

    /**
     * @Route("/{slug}", name="mosque")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueAction(Request $request, Mosque $mosque) {

        return $this->render('mosque/mosque.html.twig', [
                    'header' => $mosque->getHeader(),
                    'footer' => $mosque->getFooter(),
                    'version' => $this->getParameter('version'),
                    "site" => $this->get("translator")->trans("prayer_mobile_site", [
                        "%site%" => $this->generateUrl("mosque", ["slug" => $mosque->getSlug()], UrlGenerator::ABSOLUTE_URL)
                    ]),
                    "supportTel" => $this->getParameter("supportTel"),
                    "supportEmail" => $this->getParameter("supportEmail"),
                    'config' => json_encode($mosque->getConfiguration()->getFormatedConfig())
        ]);
    }

    /**
     * @Route("/{slug}/date")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function currentDateAjaxAction(Request $request, Mosque $mosque) {

        $mosqueService = $this->get("app.prayer_times_service");
        $date = $mosqueService->getCurrentFormtatedtDate($mosque->getConfiguration()->getLang());
        return new Response($date);
    }

    /**
     * @Route("/{slug}/has-been-updated/{lastUpdatedDate}")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function hasUpdatedAjaxAction(Request $request, Mosque $mosque, $lastUpdatedDate) {
        $hasBeenUpdated = $this->get("app.prayer_times_service")->mosqueHasBeenUpdated($mosque, $lastUpdatedDate);
        $response = [
            "hasBeenUpdated" => $hasBeenUpdated,
            "lastUpdatedDate" => $mosque->getUpdated(),
        ];
        return new JsonResponse($response, 200);
    }

}
