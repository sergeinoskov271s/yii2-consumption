Error.stackTraceLimit = 1000;

if (!process.env.NODE_ENV) {
  process.env.NODE_ENV = 'development';
}

if (process.env.NODE_ENV == 'development' && process.env.DEV_TRACE) {
  require('trace'); // active long stack trace
  //require('clarify');
}

var path = require('path');
var fs = require('fs');
var _ = require('lodash');

module.exports = {
  "port":       process.env.PORT || 3000,
  "host":       process.env.HOST || '0.0.0.0',
  "siteHost":   process.env.SITE_HOST || "",
  "staticHost": process.env.STATIC_HOST || ""
};

_.assign(module.exports, {
//  "siteHost":   "http://127.0.0.1:3000",
  "mongoose":    {
    "uri":     "mongodb://localhost/" + (process.env.NODE_ENV == 'test' ? "js_test" : "js"),
    "options": {
      "server": {
        "socketOptions": {
          "keepAlive": 1
        },
        "poolSize":      5
      }
    }
  },
//  session:       {
//    keys: [secret.sessionKey]
//  },
//  payments:      secret.payments,
//  adminKey:      secret.adminKey,
//  authProviders: secret.authProviders,
  mailer:        {
    senders: {
      default: {
        email:     'JavaScript.ru <inform@javascript.ru>',
        signature: "<p>Yours, robot</p>"
      }
    },
//    ses:     secret.ses
  },
  template:      {
    options: {
      cache: process.env.NODE_ENV !== 'development'
    }
  },
  crypto:        {
    hash: {
      length:     128,
      // may be slow(!): iterations = 12000 take ~60ms to generate strong password
      iterations: process.env.NODE_ENV === 'prod' ? 12000 : 1
    }
  },
  projectRoot:   process.cwd(),
  //publicRoot:    path.join(process.cwd(), 'public'),
  tmpRoot:  path.join(process.cwd(), '/tmp'),
  //projectCss: 'css',
  projectJsBudget: 'web/js/budget',
  projectJsOutlay: 'web/js/outlay',
  projectJsTemplate: 'web/js/template',
  projectJsTag: 'web/js/tag'
});
