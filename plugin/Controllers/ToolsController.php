<?php

namespace GeminiLabs\SiteReviews\Controllers;

use GeminiLabs\SiteReviews\Commands\ImportSettings;
use GeminiLabs\SiteReviews\Database\OptionManager;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Modules\Console;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Migrate;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Modules\System;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Role;

class ToolsController extends Controller
{
    /**
     * @return void
     * @action site-reviews/route/admin/clear-console
     */
    public function clearConsole()
    {
        glsr(Console::class)->clear();
        glsr(Notice::class)->addSuccess(_x('Console cleared.', 'admin-text', 'site-reviews'));
    }

    /**
     * @return void
     * @action site-reviews/route/ajax/clear-console
     */
    public function clearConsoleAjax()
    {
        $this->clearConsole();
        wp_send_json_success([
            'console' => glsr(Console::class)->get(),
            'notices' => glsr(Notice::class)->get(),
        ]);
    }

    /**
     * @return void
     * @action site-reviews/route/admin/detect-ip-address
     */
    public function detectIpAddress()
    {
        $link = glsr(Builder::class)->a([
            'data-expand' => '#faq-19',
            'href' => admin_url('edit.php?post_type='.glsr()->post_type.'&page=documentation#tab-faq'),
            'text' => _x('FAQ', 'admin-text', 'site-reviews'),
        ]);
        if ('unknown' === $ipAddress = Helper::getIpAddress()) {
            glsr(Notice::class)->addWarning(sprintf(
                _x('Site Reviews was unable to detect an IP address. To fix this, please see the %s.', 'admin-text', 'site-reviews'),
                $link
            ));
        } else {
            glsr(Notice::class)->addSuccess(sprintf(
                _x('Your detected IP address is %s. If this looks incorrect, please see the %s.', 'admin-text', 'site-reviews'),
                '<code>'.$ipAddress.'</code>', $link
            ));
        }
    }

    /**
     * @return void
     * @action site-reviews/route/ajax/detect-ip-address
     */
    public function detectIpAddressAjax()
    {
        $this->detectIpAddress();
        wp_send_json_success([
            'notices' => glsr(Notice::class)->get(),
        ]);
    }

    /**
     * @return void
     * @action site-reviews/route/admin/download-console
     */
    public function downloadConsole()
    {
        $this->download(glsr()->id.'-console.txt', glsr(Console::class)->get());
    }

    /**
     * @return void
     * @action site-reviews/route/admin/download-system-info
     */
    public function downloadSystemInfo()
    {
        $this->download(glsr()->id.'-system-info.txt', glsr(System::class)->get());
    }

    /**
     * @return void
     * @action site-reviews/route/admin/export-settings
     */
    public function exportSettings()
    {
        $this->download(glsr()->id.'-settings.json', glsr(OptionManager::class)->json());
    }

    /**
     * @return void
     * @action site-reviews/route/admin/fetch-console
     */
    public function fetchConsole()
    {
        glsr(Notice::class)->addSuccess(_x('Console reloaded.', 'admin-text', 'site-reviews'));
    }

    /**
     * @return void
     * @action site-reviews/route/ajax/fetch-console
     */
    public function fetchConsoleAjax()
    {
        $this->fetchConsole();
        wp_send_json_success([
            'console' => glsr(Console::class)->get(),
            'notices' => glsr(Notice::class)->get(),
        ]);
    }

    /**
     * @return void
     * @action site-reviews/route/admin/import-settings
     */
    public function importSettings()
    {
        $this->execute(new ImportSettings());
    }

    /**
     * @return void
     * @action site-reviews/route/admin/migrate-plugin
     */
    public function migratePlugin(Request $request)
    {
        if (wp_validate_boolean($request->alt)) {
            glsr(Migrate::class)->runAll();
            glsr(Notice::class)->clear()->addSuccess(_x('All plugin migrations have been run successfully.', 'admin-text', 'site-reviews'));
        } else {
            glsr(Migrate::class)->run();
            glsr(Notice::class)->clear()->addSuccess(_x('The plugin has been migrated sucessfully.', 'admin-text', 'site-reviews'));
        }
    }

    /**
     * @return void
     * @action site-reviews/route/ajax/migrate-plugin
     */
    public function migratePluginAjax(Request $request)
    {
        $this->migratePlugin($request);
        wp_send_json_success([
            'notices' => glsr(Notice::class)->get(),
        ]);
    }

    /**
     * @return void
     * @action site-reviews/route/admin/reset-permissions
     */
    public function resetPermissions()
    {
        glsr(Role::class)->resetAll();
        glsr(Notice::class)->clear()->addSuccess(_x('The permissions have been reset.', 'admin-text', 'site-reviews'));
    }

    /**
     * @return void
     * @action site-reviews/route/ajax/reset-permissions
     */
    public function resetPermissionsAjax()
    {
        glsr(Role::class)->resetAll();
        $reloadLink = glsr(Builder::class)->a([
            'text' => _x('reload the page', 'admin-text', 'site-reviews'),
            'href' => 'javascript:window.location.reload(1)',
        ]);
        glsr(Notice::class)->clear()->addSuccess(
            sprintf(_x('The permissions have been reset, please %s for them to take effect.', 'admin-text', 'site-reviews'), $reloadLink)
        );
        wp_send_json_success([
            'notices' => glsr(Notice::class)->get(),
        ]);
    }
}