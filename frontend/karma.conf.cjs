// Configuración de Karma para ejecutar tests en Docker (Chromium como root).
// ChromeHeadlessCI usa --no-sandbox y flags recomendados para contenedores.
// Incluimos frameworks para que al usar este archivo no se pierda Jasmine (describe/it/expect).
module.exports = function (config) {
  config.set({
    frameworks: ['jasmine'],
    customLaunchers: {
      ChromeHeadlessCI: {
        base: 'ChromeHeadless',
        flags: [
          '--no-sandbox',
          '--disable-gpu',
          '--disable-dev-shm-usage',
          '--disable-setuid-sandbox',
        ],
      },
    },
    browsers: ['ChromeHeadlessCI'],
  });
};
