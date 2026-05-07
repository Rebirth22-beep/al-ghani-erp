/* eslint-disable no-undef */
/**
 * ESLint config for Al-Ghani ERP frontend.
 *
 * Beginner-friendly defaults: enforce React rules + hook rules,
 * but don't block on stylistic noise.
 */
module.exports = {
  root: true,
  env: {
    browser: true,
    es2022: true,
    node: true,
  },
  extends: [
    'eslint:recommended',
    'plugin:react/recommended',
    'plugin:react/jsx-runtime',
    'plugin:react-hooks/recommended',
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
    ecmaFeatures: { jsx: true },
  },
  settings: {
    react: { version: '18.3' },
  },
  plugins: ['react-refresh'],
  rules: {
    // React 17+ doesn't need React in scope
    'react/react-in-jsx-scope': 'off',
    'react/prop-types': 'off',

    // HMR boundary check — warn rather than error so the lint script stays usable
    'react-refresh/only-export-components': ['warn', { allowConstantExport: true }],

    // Allow unused vars when prefixed with _ (handler placeholders, etc.)
    'no-unused-vars': ['warn', {
      argsIgnorePattern: '^_',
      varsIgnorePattern: '^_',
      ignoreRestSiblings: true,
    }],

    // Use === / !==
    eqeqeq: ['error', 'always'],
  },
}
