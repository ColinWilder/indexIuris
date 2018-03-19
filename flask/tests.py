import os
import api
import unittest
import tempfile


from contextlib import contextmanager
from io  import StringIO
import sys

@contextmanager
def captured_output():
    new_out, new_err = StringIO(), StringIO()
    old_out, old_err = sys.stdout, sys.stderr
    try:
        sys.stdout, sys.stderr = new_out, new_err
        yield sys.stdout, sys.stderr
    finally:
        sys.stdout, sys.stderr = old_out, old_err

class apiTestCase(unittest.TestCase):

    def setUp(self):
        #self.db_fd, api.app.config['DATABASE'] = tempfile.mkstemp()
        api.app.testing = True
        self.client = api.app.test_client()
        #with api.app.app_context():
        #    api.init_db()

    def tearDown(self):
        print ('\nteardown')
        #os.close(self.db_fd)
        #os.unlink(flaskr.app.config['DATABASE'])

    def test_missing_q(self):
        rv = self.client.get('/simplesearch')
        assert b'Parameter not set' in rv.data
        rv = self.client.get('/simplesearch?start=0')
        assert b'Parameter not set' in rv.data

    def test_missing_start(self):
        rv = self.client.get('/simplesearch')
        assert b'Parameter not set' in rv.data
        rv = self.client.get('/simplesearch?q=q')
        assert b'Parameter not set' in rv.data

    def test_rows_value_error(self):
        with captured_output() as (out, err):
            rv = self.client.get('/simplesearch?q=q&start=0&rows=abc')
        output = out.getvalue().strip()
        assert 'Rows value error' in output
    
    def test_start_value_error(self):
        with captured_output() as (out, err):
            rv = self.client.get('/simplesearch?q=q&start=abc&rows=0')
        output = out.getvalue().strip()
        assert 'Start value error' in output

    def test_archive_not_set(self):
        with captured_output() as (out, err):
            rv = self.client.get('/simplesearch?q=q&start=0&rows=0')
        output = out.getvalue().strip()
        assert 'archive not set' in output

    def test_archive_set(self):
        with captured_output() as (out, err):
            rv = self.client.get('/simplesearch?q=q&start=0&rows=0&archive=a')
        output = out.getvalue().strip()
        assert 'archive not set' not in output


if __name__ == '__main__':
    unittest.main()

