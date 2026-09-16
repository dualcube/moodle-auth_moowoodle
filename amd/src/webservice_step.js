// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Refreshes the Web Service step's Token list when the service or user dropdown
 * changes, via a small JSON fetch, instead of reloading the page. No page navigation
 * means no "leave this page?" prompt from Moodle's unsaved-changes warning.
 *
 * The token <select> must carry a "data-placeholder" attribute with the translated
 * placeholder text, since its options are fully replaced on every refresh.
 *
 * @module     auth_moowoodle/webservice_step
 * @copyright  2023 DualCube Team(https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @param {string} ajaxurl URL of wizard_ajax.php.
 */
export const init = (ajaxurl) => {
    const serviceSelect = document.getElementById('auth_moowoodle_serviceid');
    const userSelect = document.getElementById('id_userid');
    const tokenSelect = document.getElementById('auth_moowoodle_token');
    const button = document.getElementById('id_updateservice');

    if (!serviceSelect || !tokenSelect) {
        return;
    }

    const tokenPlaceholder = tokenSelect.dataset.placeholder || '';

    const refreshTokens = () => {
        if (serviceSelect.value === '') {
            return;
        }

        const params = new URLSearchParams({
            sesskey: M.cfg.sesskey,
            serviceid: serviceSelect.value,
            userid: userSelect ? userSelect.value : 0,
        });

        fetch(ajaxurl + '?' + params.toString(), {credentials: 'same-origin'})
            .then((response) => response.json())
            .then((data) => {
                tokenSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = tokenPlaceholder;
                tokenSelect.appendChild(placeholder);

                Object.keys(data.tokens).forEach((token) => {
                    const option = document.createElement('option');
                    option.value = token;
                    option.textContent = data.tokens[token];
                    option.selected = (token === data.selectedtoken);
                    tokenSelect.appendChild(option);
                });

                if (button) {
                    button.value = data.buttonlabel;
                }
            })
            .catch(() => {
                // Leave the current token list as-is on a network error.
            });
    };

    serviceSelect.addEventListener('change', refreshTokens);
    if (userSelect) {
        userSelect.addEventListener('change', refreshTokens);
    }
};
