(() => {
  const addServiceStatus = () => {
    const footer = document.getElementById('footer');
    const websiteLink = footer?.querySelector(
      'a[href="https://pay247.io"], a[href="https://pay247.io/"]'
    );

    if (!websiteLink || footer.querySelector('#pay247-service-status')) return;

    const badge = document.createElement('iframe');
    badge.id = 'pay247-service-status';
    badge.src = 'https://status.pay247.io/badge?theme=light';
    badge.title = 'PAY247 service status';
    badge.width = '250';
    badge.height = '30';
    badge.setAttribute('frameborder', '0');
    badge.setAttribute('scrolling', 'no');
    badge.loading = 'lazy';
    badge.style.colorScheme = 'normal';

    websiteLink.parentElement.classList.add('pay247-footer-links');
    footer.classList.add('pay247-status-footer');
    websiteLink.insertAdjacentElement('afterend', badge);
  };

  // Older Mintlify versions replace the content container without a navigate event.
  // Watch only direct layout children, not changes inside large document tables.
  let observedContent;
  const observeLayout = () => {
    const content = document.getElementById('content-container') || document.body;
    if (content === observedContent) return;
    observer.disconnect();
    observedContent = content;
    for (let node = content; node; node = node.parentElement) {
      observer.observe(node, { childList: true });
    }
  };
  const observer = new MutationObserver(() => {
    addServiceStatus();
    observeLayout();
  });

  addServiceStatus();
  observeLayout();
  window.addEventListener('mintlify:navigate', addServiceStatus);
})();
