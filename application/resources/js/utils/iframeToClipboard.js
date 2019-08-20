import { writeText } from 'clipboard-polyfill';

window.iframeToClipboard = (iframeId) => {
    const iFrame = document.getElementById(iframeId);

    if (iFrame === null) {
        throw new Error(`iframe ${iframeId} does not seem to exist`);
    }

    const content = iFrame.contentDocument || iFrame.contentWindow.document;
    writeText(content.documentElement.outerHTML);
};
