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
 * Copy-to-clipboard buttons for the setup wizard's read-only site URL / token fields.
 *
 * A trigger button needs class "auth-moowoodle-copy", a "data-copy-target" attribute
 * naming the id of the element to copy from, and a "data-copied-label" attribute with
 * the translated "Copied" text to show for 2 seconds after a successful copy.
 *
 * @module     auth_moowoodle/copy_button
 * @copyright  2023 DualCube Team(https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

let registered = false;

/**
 * Register the click handler for all copy buttons on the page. Safe to call more than
 * once; only the first call attaches the listener.
 */
export const init = () => {
    if (registered) {
        return;
    }
    registered = true;

    document.addEventListener('click', (event) => {
        const button = event.target.closest('.auth-moowoodle-copy');
        if (!button) {
            return;
        }

        const target = document.getElementById(button.getAttribute('data-copy-target'));
        if (!target) {
            return;
        }

        const text = 'value' in target ? target.value : target.textContent;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text);
        } else {
            target.select();
            document.execCommand('copy');
        }

        if (!button.dataset.originalLabel) {
            button.dataset.originalLabel = button.textContent;
        }

        clearTimeout(button.moowoodleCopyTimeout);
        button.textContent = button.dataset.copiedLabel;
        button.moowoodleCopyTimeout = setTimeout(() => {
            button.textContent = button.dataset.originalLabel;
        }, 2000);
    });
};
