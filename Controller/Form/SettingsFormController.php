<?php

declare(strict_types=1);

namespace Plugin\Landswitcher\Controller\Form;

use Exception;
use JTL\Smarty\JTLSmarty;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use JTL\Router\Controller\AbstractController;
use Laminas\Diactoros\Response\EmptyResponse;
use Plugin\Landswitcher\Manager\NonceManager;
use Plugin\Landswitcher\Model\TLandswitcherRedirectsModel;

final class SettingsFormController extends AbstractController
{
    public function save(ServerRequestInterface $request, array $args, JTLSmarty $smarty): ResponseInterface
    {
        $data = $request->getParsedBody();
        $redirectsData = json_decode($data['redirectsData'] ?? '[]', true);

        if (!is_array($redirectsData)) {
            return new JsonResponse('Invalid data format', 400, ['Content-Type' => 'application/json']);
        }

        if (!$this->db) {
            return new JsonResponse('Database connection is not available', 500);
        }

        $nonce = new NonceManager();

        if (!isset($data['settings_form_nonce']) && $nonce->verify($data['settings_form_nonce'])) {
            return new JsonResponse('Invalid nonce', 403, ['Content-Type' => 'application/json']);
        }

        foreach ($redirectsData as $redirect) {
            $cISO = $redirect['cISO'] ?? '';
            $url = $redirect['url'] ?? '/';

            if (empty($url) || empty($cISO))
                continue;

            try {
                $redirectModel = TLandswitcherRedirectsModel::loadByAttributes(['cISO' => $cISO], $this->db);
            } catch (Exception $e) {
                $redirectModel = new TLandswitcherRedirectsModel($this->db);
                $redirectModel->setCISO($cISO);
            }

            try {
                $redirectModel->setUrl($url);
                $redirectModel->save();
            } catch (Exception $e) {
                return new JsonResponse('Error: ' . $e->getMessage(), 500, ['Content-Type' => 'application/json']);
            }
        }

        return new JsonResponse('Updated successfully', 200, ['Content-Type' => 'application/json']);
    }

    public function getResponse(ServerRequestInterface $request, array $args, JTLSmarty $smarty): ResponseInterface
    {
        return new EmptyResponse(200);
    }
}
